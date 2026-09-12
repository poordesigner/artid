<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SupportContext
{
    public static function cacheKey(string $topic): string
    {
        return 'support_context:'.$topic;
    }

    /**
     * Invalida el cache de todos los paquetes de contexto.
     * Se llama al editar algo que afecta el conocimiento (paquetes/funciones).
     */
    public static function forgetAll(): void
    {
        foreach (array_keys(config('support_packs.packs', [])) as $topic) {
            Cache::forget(self::cacheKey($topic));
        }
    }
}