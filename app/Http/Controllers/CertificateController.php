<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function store(Request $request, Artwork $artwork)
    {
        $artist = $request->user();
        if ($artwork->artist_id !== $artist->id) abort(403);

        if (! $artwork->isQrValidated()) {
            return back()->with('error', __('Debes validar los datos y generar el QR definitivo antes de emitir el COA.'));
        }
        if ($artwork->certificates()->where('status', 'valid')->exists()) {
            return back()->with('error', __('Ya existe un COA válido para esta obra. Edita el Texto Descriptivo o revócalo para generar uno nuevo.'));
        }

        $validated = $request->validate([
            'custom_text' => ['nullable', 'string', 'max:800'],
            'include_signature' => ['nullable', 'boolean'],
            'accept_terms' => ['required', 'accepted'],
        ]);

        $includeSignature = (bool) ($validated['include_signature'] ?? false);
        if ($includeSignature && ! $artist->signature_image) {
            return back()->with('error', __('Debes subir tu firma manuscrita en tu perfil antes de incluirla en el COA.'));
        }

        // Token cost from TokenFunction "Generar COA" (0 = gratis/promo)
        $cost = $this->coaCost();
        if ($cost > 0 && $artist->tokenBalance() < $cost) {
            return back()->with('error', __('No tienes tokens suficientes para generar el COA. Costo: :cost', ['cost' => $cost]));
        }

        // Resolve holder snapshot
        $ownership = $artwork->ownerships()->latest()->first();
        $holderName = $ownership?->owner_name ?? $artist->name;
        $holderType = $ownership?->type ?? 'initial';
        $issuedLocation = $artwork->creation_location ?? '';

        $certificate = DB::transaction(function () use ($artist, $artwork, $validated, $includeSignature, $holderName, $holderType, $issuedLocation, $cost, $request) {
            // Consecutivo bloqueado por año
            $year = now()->year;
            $prefix = "COA-$year-";
            $last = Certificate::where('certificate_number', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('certificate_number')
                ->first();
            $nextNum = $last ? ((int) substr($last->certificate_number, -6)) + 1 : 1;
            $certNumber = sprintf('COA-%d-%06d', $year, $nextNum);

            // Consume token (si costo 0 no consume)
            if ($cost > 0 && ! $artist->consumeToken("COA $certNumber - {$artwork->title}")) {
                throw new \RuntimeException('Sin tokens');
            }

            // Hashes con 6 campos obligatorios del QR (título, imagen, año, edición, nº copias, dimensiones)
            $imageHash = null;
            if ($artwork->image) {
                $path = "artworks/{$artwork->artwork_id}/{$artwork->image}";
                if (Storage::disk('r2')->exists($path)) {
                    $imageHash = hash('sha256', Storage::disk('r2')->get($path));
                }
            }
            $totalCopies = null;
            if ($artwork->edition && str_contains($artwork->edition, '/')) {
                $totalCopies = trim(explode('/', $artwork->edition)[1] ?? '');
            }
            $metadata = json_encode([
                'title' => $artwork->title,
                'image_hash' => $imageHash,
                'year' => $artwork->year,
                'edition' => $artwork->edition,
                'total_copies' => $totalCopies,
                'edition_number' => $artwork->edition_number,
                'dimensions' => $artwork->dimensions,
                'holder' => $holderName,
                'cert' => $certNumber,
            ], JSON_UNESCAPED_UNICODE);
            $metaHash = hash('sha256', $metadata);

            $cert = Certificate::create([
                'artwork_id' => $artwork->id,
                'artist_id' => $artist->id,
                'certificate_number' => $certNumber,
                'issued_at' => now(),
                'issued_location' => $issuedLocation,
                'holder_name' => $holderName,
                'holder_type' => $holderType,
                'custom_text' => $validated['custom_text'] ?? null,
                'include_signature' => $includeSignature,
                'hash_image' => $imageHash,
                'hash_metadata' => $metaHash,
                'qr_payload' => $artwork->signedUrl(),
                'status' => 'valid',
                'terms_version' => config('qrte.legal_version'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Huella legal
            \App\Models\LegalConsent::create([
                'artist_id' => $artist->id,
                'type' => 'coa',
                'version' => config('qrte.legal_version'),
                'granted' => true,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $cert;
        });

        try {
            $this->generatePdf($certificate);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('COA PDF gen failed: '.$e->getMessage());
        }

        return back()->with('status', __('COA :num generado. Ya puedes descargarlo/reimprimir.', ['num' => $certificate->certificate_number]));
    }

    public function download(Request $request, Artwork $artwork, Certificate $certificate)
    {
        if ($artwork->artist_id !== $request->user()->id || $certificate->artwork_id !== $artwork->id) abort(403);
        try {
            if (! $certificate->pdf_path || ! Storage::disk('r2')->exists($certificate->pdf_path)) {
                $this->generatePdf($certificate);
                $certificate->refresh();
            }
            $isHtml = str_ends_with($certificate->pdf_path, '.html');
            $filename = $certificate->certificate_number.($isHtml ? '.html' : '.pdf');
            $content = Storage::disk('r2')->get($certificate->pdf_path);
            return response($content, 200, [
                'Content-Type' => $isHtml ? 'text/html' : 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Content-Length' => strlen($content),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('COA download failed: '.$e->getMessage().' @ '.$e->getFile().':'.$e->getLine());
            // Fallback: render HTML en vivo para no dar 500
            try {
                $qrFichaBase64 = $this->qrBase64($certificate->qr_payload);
                $verifyUrl = rtrim(config('qrte.public_url'), '/').'/coa/'.$certificate->certificate_number.'/verify';
                $qrVerifyBase64 = $this->qrBase64($verifyUrl);
                $hashCombined = hash('sha256', ($certificate->hash_image ?? 'no-image').($certificate->hash_metadata ?? ''));
                return view('certificates.pdf', [
                    'cert' => $certificate, 'artwork' => $certificate->artwork, 'artist' => $certificate->artist,
                    'qrBase64' => $qrFichaBase64, 'qrFichaBase64' => $qrFichaBase64, 'qrVerifyBase64' => $qrVerifyBase64,
                    'hashCombined' => $hashCombined, 'artworkBase64' => null, 'artworkMime' => 'image/jpeg',
                    'sigBase64' => null, 'sigMime' => 'image/png', 'isPreview' => false,
                ]);
            } catch (\Throwable $e2) {
                abort(500, 'COA download error: '.$e->getMessage());
            }
        }
    }

    public function revoke(Request $request, Artwork $artwork, Certificate $certificate)
    {
        if ($artwork->artist_id !== $request->user()->id || $certificate->artwork_id !== $artwork->id) abort(403);
        if ($certificate->status === 'revoked') return back();
        $certificate->update(['status' => 'revoked', 'revoked_at' => now()]);
        try {
            $this->generatePdf($certificate->fresh());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('COA revoke PDF regen failed: '.$e->getMessage());
        }
        return back()->with('status', __('COA revocado.'));
    }

    public function preview(Request $request, Artwork $artwork)
    {
        if ($artwork->artist_id !== $request->user()->id) abort(403);
        $data = $request->validate([
            'custom_text' => ['nullable', 'string', 'max:800'],
            'include_signature' => ['nullable', 'boolean'],
        ]);
        $artist = $request->user();
        $previewCert = new Certificate([
            'certificate_number' => 'COA-PREVIEW — VISTA PREVIA (no válido)',
            'issued_at' => now(),
            'issued_location' => $artwork->creation_location ?? '',
            'holder_name' => $artwork->ownerships()->latest()->first()?->owner_name ?? $artist->name,
            'holder_type' => $artwork->ownerships()->latest()->first()?->type ?? 'initial',
            'custom_text' => $data['custom_text'] ?? null,
            'include_signature' => (bool) ($data['include_signature'] ?? false),
            'hash_image' => 'PREVIEW',
            'hash_metadata' => 'PREVIEW',
            'qr_payload' => $artwork->signedUrl(),
            'status' => 'valid',
        ]);
        $previewCert->setRelation('artwork', $artwork);
        $previewCert->setRelation('artist', $artist);
        $qrFichaBase64 = $this->qrBase64($previewCert->qr_payload);
        $verifyUrl = rtrim(config('qrte.public_url'), '/').'/coa/PREVIEW/verify';
        $qrVerifyBase64 = $this->qrBase64($verifyUrl);
        $hashCombined = 'PREVIEW';
        $artworkBase64 = null; $artworkMime='image/jpeg'; $sigBase64=null; $sigMime='image/png';
        try {
            if ($artwork->image && Storage::disk('r2')->exists("artworks/{$artwork->artwork_id}/{$artwork->image}")) {
                $bin = Storage::disk('r2')->get("artworks/{$artwork->artwork_id}/{$artwork->image}");
                $ext = strtolower(pathinfo($artwork->image, PATHINFO_EXTENSION));
                [$bin, $artworkMime] = $this->toJpegBase64($bin, $ext);
                $artworkBase64 = base64_encode($bin);
            }
            if ($artist->signature_image && Storage::disk('r2')->exists("artists/{$artist->id}/{$artist->signature_image}")) {
                $bin = Storage::disk('r2')->get("artists/{$artist->id}/{$artist->signature_image}");
                $ext = strtolower(pathinfo($artist->signature_image, PATHINFO_EXTENSION));
                [$bin, $sigMime] = $this->toJpegBase64($bin, $ext);
                $sigBase64 = base64_encode($bin);
            }
        } catch (\Throwable $e) {}
        return view('certificates.pdf', ['cert' => $previewCert, 'artwork' => $artwork, 'artist' => $artist, 'qrBase64' => $qrFichaBase64, 'qrFichaBase64'=>$qrFichaBase64, 'qrVerifyBase64'=>$qrVerifyBase64, 'hashCombined'=>$hashCombined, 'artworkBase64'=>$artworkBase64, 'artworkMime'=>$artworkMime, 'sigBase64'=>$sigBase64, 'sigMime'=>$sigMime, 'isPreview' => true]);
    }

    public function verify(string $certificateNumber)
    {
        $cert = Certificate::where('certificate_number', $certificateNumber)->with(['artwork', 'artist'])->firstOrFail();
        $hashCombined = hash('sha256', ($cert->hash_image ?? 'no-image').($cert->hash_metadata ?? ''));
        return view('certificates.verify', compact('cert', 'hashCombined'));
    }

    public function update(Request $request, Artwork $artwork, Certificate $certificate)
    {
        if ($artwork->artist_id !== $request->user()->id || $certificate->artwork_id !== $artwork->id) abort(403);
        if ($certificate->status !== 'valid') return back()->with('error', __('Solo se puede editar un COA válido.'));
        $validated = $request->validate([
            'custom_text' => ['nullable', 'string', 'max:800'],
        ]);
        $certificate->update(['custom_text' => $validated['custom_text'] ?? null]);
        try { $this->generatePdf($certificate->fresh()); } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('COA update PDF failed: '.$e->getMessage());
        }
        return back()->with('status', __('Texto Descriptivo actualizado.'));
    }

    private function coaCost(): int
    {
        $func = \App\Models\TokenFunction::where('name', 'Generar Certificado de Autenticidad (COA)')->first();
        return $func ? (int) $func->tokens : 1;
    }

    private function qrBase64(string $payload): ?string
    {
        // Intentar PNG, fallback a SVG (más compatible con Dompdf)
        try {
            $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(240)->margin(1)->generate($payload);
            return base64_encode($svg);
        } catch (\Throwable $e) {
            try {
                $png = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(240)->margin(1)->generate($payload);
                return base64_encode($png);
            } catch (\Throwable $e2) {
                \Illuminate\Support\Facades\Log::error('QR gen failed: '.$e->getMessage());
                return null;
            }
        }
    }

    private function qrBase64Mime(string $payload): array
    {
        try {
            $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(240)->margin(1)->generate($payload);
            return [base64_encode($svg), 'image/svg+xml'];
        } catch (\Throwable $e) {
            $b64 = $this->qrBase64($payload);
            return [$b64, $b64 ? 'image/png' : null];
        }
    }

    private function toJpegBase64(string $bin, string $ext): array
    {
        if ($ext !== 'webp') return [$bin, $ext === 'png' ? 'image/png' : 'image/jpeg'];
        $img = @\imagecreatefromstring($bin);
        if ($img === false) return [$bin, 'image/webp'];
        ob_start();
        \imagejpeg($img, null, 85);
        $jpeg = ob_get_clean();
        \imagedestroy($img);
        return [$jpeg, 'image/jpeg'];
    }

    private function generatePdf(Certificate $cert): void
    {
        $artwork = $cert->artwork;
        $artist = $cert->artist;
        $qrFichaBase64 = $this->qrBase64($cert->qr_payload);
        $verifyUrl = rtrim(config('qrte.public_url'), '/').'/coa/'.$cert->certificate_number.'/verify';
        $qrVerifyBase64 = $this->qrBase64($verifyUrl);
        $hashCombined = hash('sha256', ($cert->hash_image ?? 'no-image').($cert->hash_metadata ?? ''));
        // Embebido base64 para Dompdf (evita fetch remoto que falla y corrompe PDF) — WEBP → JPEG
        $artworkBase64 = null;
        $artworkMime = 'image/jpeg';
        if ($artwork->image) {
            try {
                $path = "artworks/{$artwork->artwork_id}/{$artwork->image}";
                if (Storage::disk('r2')->exists($path)) {
                    $bin = Storage::disk('r2')->get($path);
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    [$bin, $artworkMime] = $this->toJpegBase64($bin, $ext);
                    $artworkBase64 = base64_encode($bin);
                }
            } catch (\Throwable $e) {}
        }
        $sigBase64 = null;
        $sigMime = 'image/png';
        if ($artist->signature_image) {
            try {
                $sp = "artists/{$artist->id}/{$artist->signature_image}";
                if (Storage::disk('r2')->exists($sp)) {
                    $bin = Storage::disk('r2')->get($sp);
                    $ext = strtolower(pathinfo($sp, PATHINFO_EXTENSION));
                    [$bin, $sigMime] = $this->toJpegBase64($bin, $ext);
                    $sigBase64 = base64_encode($bin);
                }
            } catch (\Throwable $e) {}
        }
        $html = view('certificates.pdf', compact('cert', 'artwork', 'artist', 'qrFichaBase64', 'qrVerifyBase64', 'hashCombined', 'artworkBase64', 'artworkMime', 'sigBase64', 'sigMime'))->render();

        if (class_exists(\Dompdf\Dompdf::class)) {
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', false);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', false);
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();
            $path = "certificates/{$artwork->artwork_id}/{$cert->certificate_number}.pdf";
            Storage::disk('r2')->put($path, $output, ['ContentType' => 'application/pdf']);
        } else {
            $path = "certificates/{$artwork->artwork_id}/{$cert->certificate_number}.html";
            Storage::disk('r2')->put($path, $html, ['ContentType' => 'text/html']);
        }
        $cert->update(['pdf_path' => $path]);
    }
}

