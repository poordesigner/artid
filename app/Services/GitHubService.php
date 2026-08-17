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
}
