<?php

namespace App\Console\Commands;

use App\Models\Artist;
use App\Models\OnboardingEmail;
use App\Support\OnboardingConditions;
use Illuminate\Console\Command;

class ProcessOnboardingEmails extends Command
{
    protected $signature = 'email:onboarding:process';

    protected $description = 'Procesa y envía las secuencias de onboarding por email a artistas elegibles.';

    public function handle(): int
    {
        $steps = config('onboarding.steps', []);
        $results = [];

        foreach ($steps as $stepKey => $stepConfig) {
            $eligibleDate = now()->subDays($stepConfig['delay_days']);

            $artists = Artist::where('is_admin', false)
                ->where('created_at', '<=', $eligibleDate)
                ->get()
                ->filter(fn (Artist $artist) => ! OnboardingEmail::alreadySent($artist->id, $stepKey))
                ->filter(fn (Artist $artist) => OnboardingConditions::evaluate($stepConfig['condition'], $artist));

            $sent = 0;

            foreach ($artists as $artist) {
                try {
                    $artist->notify(new $stepConfig['notification']($stepKey));
                    OnboardingEmail::markSent($artist->id, $stepKey);
                    $sent++;
                } catch (\Throwable $e) {
                    $this->error("Error enviando {$stepKey} a {$artist->email}: {$e->getMessage()}");
                }
            }

            $results[$stepKey] = $sent;
            $this->info("Step [{$stepKey}]: {$sent} emails enviados.");
        }

        $total = array_sum($results);
        $this->info("Total: {$total} emails de onboarding enviados.");

        return self::SUCCESS;
    }
}
