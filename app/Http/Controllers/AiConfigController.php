<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AiConfigController extends Controller
{
    public const CACHE_KEY = 'ai_config';

    public const DEFAULTS = [
        'router_model' => 'qwen/qwen3.8-27b',
        'chat_model' => 'qwen/qwen3.8-27b',
        'backup_model' => null,
    ];

    public function config(): JsonResponse
    {
        $config = Cache::remember(self::CACHE_KEY, 300, function () {
            return [
                'router_model' => (string) AppSetting::get('ai.router_model', self::DEFAULTS['router_model']),
                'chat_model' => (string) AppSetting::get('ai.chat_model', self::DEFAULTS['chat_model']),
                'backup_model' => AppSetting::get('ai.backup_model') ?: null,
            ];
        });

        return response()->json($config);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'router_model' => ['required', 'string', 'max:255'],
            'chat_model' => ['required', 'string', 'max:255'],
            'backup_model' => ['nullable', 'string', 'max:255'],
        ]);

        AppSetting::set('ai.router_model', $validated['router_model']);
        AppSetting::set('ai.chat_model', $validated['chat_model']);
        AppSetting::set('ai.backup_model', $validated['backup_model'] ?: null);

        Cache::forget(self::CACHE_KEY);

        return back()->with('status', 'ai-updated');
    }
}