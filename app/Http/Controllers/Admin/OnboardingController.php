<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\OnboardingEmail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index()
    {
        $steps = config('onboarding.steps', []);
        $totalArtists = Artist::where('is_admin', false)->count();
        $stepStats = [];

        foreach ($steps as $stepKey => $stepConfig) {
            $sentCount = OnboardingEmail::where('step', $stepKey)->count();
            $eligibleCount = Artist::where('is_admin', false)
                ->where('created_at', '<=', now()->subDays($stepConfig['delay_days']))
                ->count();
            $pendingCount = $eligibleCount - $sentCount;

            $stepStats[$stepKey] = [
                'delay_days' => $stepConfig['delay_days'],
                'condition' => $stepConfig['condition'],
                'sent' => $sentCount,
                'eligible' => $eligibleCount,
                'pending' => max(0, $pendingCount),
            ];
        }

        $completedCount = OnboardingEmail::select('artist_id')
            ->distinct()
            ->count('artist_id');

        return view('admin.onboarding', compact('totalArtists', 'stepStats', 'completedCount'));
    }

    public function process(Request $request)
    {
        $exitCode = Artisan::call('email:onboarding:process');
        $output = Artisan::output();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $exitCode === 0,
                'output' => $output,
            ]);
        }

        return redirect()->route('admin.onboarding')
            ->with('status', __('Proceso de onboarding ejecutado.'));
    }
}
