<?php

namespace App\Http\Controllers;

use App\Models\TokenPackage;
use App\Services\PaddleService;
use Illuminate\Http\Request;

class TokenPackageController extends Controller
{
    public function index()
    {
        return redirect()->route('configuracion', ['tab' => 'packages']);
    }

    public function store(Request $request, PaddleService $paddle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'price_usd' => 'required|numeric|min:0.5',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $package = TokenPackage::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'price_usd' => $validated['price_usd'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncToPaddle($package, $paddle);

        return redirect()->route('configuracion', ['tab' => 'packages'])
            ->with('status', __('Paquete de tokens creado.'));
    }

    public function update(Request $request, TokenPackage $package, PaddleService $paddle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'price_usd' => 'required|numeric|min:0.5',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'tokens' => $validated['tokens'],
            'price_usd' => $validated['price_usd'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        $this->syncToPaddle($package, $paddle);

        return redirect()->route('configuracion', ['tab' => 'packages'])
            ->with('status', __('Paquete de tokens actualizado.'));
    }

    public function destroy(TokenPackage $package, PaddleService $paddle)
    {
        if ($package->paddle_product_id) {
            try {
                $this->clientDelete($paddle, '/products/'.$package->paddle_product_id);
            } catch (\Throwable $e) {
                // Si Paddle rechaza (ej. ya tiene transacciones), lo archiva igual localmente.
            }
        }

        $package->delete();

        return redirect()->route('configuracion', ['tab' => 'packages'])
            ->with('status', __('Paquete de tokens eliminado.'));
    }

    /**
     * Recrea producto + precio one-time en Paddle y guarda los ids.
     */
    private function syncToPaddle(TokenPackage $package, PaddleService $paddle): void
    {
        if (! config('paddle.api_key')) {
            return;
        }

        try {
            if (! $package->paddle_product_id) {
                $product = $paddle->createTokenProduct($package);
                $package->paddle_product_id = $product['id'];
            }

            if (! $package->paddle_price_id) {
                $price = $paddle->createTokenPrice($package);
                $package->paddle_price_id = $price['id'];
            }

            $package->save();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function clientDelete(PaddleService $paddle, string $url): void
    {
        \Illuminate\Support\Facades\Http::baseUrl(rtrim((string) config('paddle.base_url'), '/'))
            ->withToken((string) config('paddle.api_key'))
            ->acceptJson()
            ->delete($url)
            ->throw();
    }
}