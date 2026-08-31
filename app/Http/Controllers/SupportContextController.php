<?php

namespace App\Http\Controllers;

use App\Support\SupportContext;
use App\Support\SupportContextBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SupportContextController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $topic = (string) $request->query('topic', config('support_packs.default_topic', 'introduccion'));

        if (! array_key_exists($topic, config('support_packs.packs', []))) {
            abort(404, 'Unknown support topic');
        }

        $content = Cache::remember(
            SupportContext::cacheKey($topic),
            (int) config('support_packs.tll_seconds', 300),
            fn () => SupportContextBuilder::build($topic),
        );

        return response()->json([
            'topic' => $topic,
            'content' => $content,
        ]);
    }
}