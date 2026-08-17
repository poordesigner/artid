<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GitHubService
{
    public function __construct(private readonly string $token) {}

    /**
     * Base HTTP client authenticated with the artist's token.
     */
    private function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken($this->token)->accept('application/vnd.github+json');
    }

    /**
     * List the user's repositories (name + full_name).
     *
     * @return array<int, array{full_name: string, name: string}>
     */
    public function listRepos(): array
    {
        $res = $this->client()->get('https://api.github.com/user/repos?per_page=100&sort=updated');

        if ($res->failed()) {
            return [];
        }

        return collect($res->json())
            ->map(fn (array $repo) => [
                'full_name' => $repo['full_name'],
                'name' => $repo['name'],
            ])
            ->all();
    }

    /**
     * Create a new repository and return its full_name.
     */
    public function createRepo(string $name): string
    {
        $res = $this->client()->post('https://api.github.com/user/repos', [
            'name' => $name,
            'private' => false,
            'auto_init' => true,
            'description' => 'Framework ARTid de identidad digital',
        ]);

        if ($res->failed()) {
            throw new RuntimeException($res->json('message') ?? 'Error creando el repositorio.');
        }

        return $res->json('full_name');
    }

    /**
     * Read a file from the repository. Returns ['sha' => ..., 'content' => ...] or null if missing.
     *
     * @return array{sha: string, content: string}|null
     */
    public function getFile(string $repo, string $path): ?array
    {
        $res = $this->client()->get("https://api.github.com/repos/$repo/contents/$path");

        if ($res->status() === 404) {
            return null;
        }

        if ($res->failed()) {
            throw new RuntimeException($res->json('message') ?? 'Error leyendo el archivo.');
        }

        return [
            'sha' => $res->json('sha'),
            'content' => base64_decode($res->json('content')),
        ];
    }

    /**
     * Create or update a file in the repository.
     */
    public function putFile(string $repo, string $path, string $content, string $message): void
    {
        $existing = $this->getFile($repo, $path);

        $payload = [
            'message' => $message,
            'content' => base64_encode($content),
        ];

        if ($existing) {
            $payload['sha'] = $existing['sha'];
        }

        $res = $this->client()->put("https://api.github.com/repos/$repo/contents/$path", $payload);

        if ($res->failed()) {
            throw new RuntimeException($res->json('message') ?? 'Error escribiendo el archivo.');
        }
    }

    /**
     * Delete a file from the repository (no-op if missing).
     */
    public function deleteFile(string $repo, string $path, string $message): void
    {
        $existing = $this->getFile($repo, $path);

        if (! $existing) {
            return;
        }

        $res = $this->client()->delete("https://api.github.com/repos/$repo/contents/$path", [
            'message' => $message,
            'sha' => $existing['sha'],
        ]);

        if ($res->failed()) {
            throw new RuntimeException($res->json('message') ?? 'Error borrando el archivo.');
        }
    }

    /**
     * Read the artworks manifest (list of artwork_id).
     *
     * @return array<int, string>
     */
    public function getManifest(string $repo): array
    {
        $file = $this->getFile($repo, 'artworks/manifest.json');

        if (! $file) {
            return [];
        }

        $data = json_decode($file['content'], true);

        return is_array($data) ? array_values(array_filter($data, 'is_string')) : [];
    }

    /**
     * Write the artworks manifest.
     *
     * @param  array<int, string>  $ids
     */
    public function saveManifest(string $repo, array $ids, string $message): void
    {
        $this->putFile(
            $repo,
            'artworks/manifest.json',
            json_encode(array_values($ids), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            $message
        );
    }

    /**
     * List the artwork IDs present in the repository.
     * Prefers the manifest; falls back to scanning the artworks/ folder.
     *
     * @return array<int, string>
     */
    public function listArtworkIds(string $repo): array
    {
        $ids = $this->getManifest($repo);

        if (! empty($ids)) {
            return $ids;
        }

        $res = $this->client()->get("https://api.github.com/repos/$repo/contents/artworks");

        if ($res->failed()) {
            return [];
        }

        $ids = [];
        foreach ($res->json() as $item) {
            if (($item['type'] ?? null) !== 'dir') {
                continue;
            }

            $name = (string) $item['name'];
            if ($this->getFile($repo, "artworks/$name/metadata.json")) {
                $ids[] = $name;
            }
        }

        return $ids;
    }
}
