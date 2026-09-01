<?php

use App\Notifications\SellTokensNotification;
use App\Notifications\SocialProofNotification;
use App\Notifications\TutorialNotification;
use App\Notifications\ReminderTokensNotification;
use App\Notifications\WelcomeOnboardingNotification;

return [

    /*
    |--------------------------------------------------------------------------
    | Secuencias de onboarding por email
    |--------------------------------------------------------------------------
    |
    | Cada step define: delay_days (desde registro), condition (para evaluar
    | si el artista califica), y notification (clase a disparar).
    |
    */

    'steps' => [

        'welcome' => [
            'delay_days' => 0,
            'condition' => 'always',
            'notification' => WelcomeOnboardingNotification::class,
        ],

        'reminder_tokens' => [
            'delay_days' => 3,
            'condition' => 'has_tokens_and_no_artworks',
            'notification' => ReminderTokensNotification::class,
        ],

        'tutorial' => [
            'delay_days' => 7,
            'condition' => 'has_no_artworks',
            'notification' => TutorialNotification::class,
        ],

        'social_proof' => [
            'delay_days' => 14,
            'condition' => 'has_artworks',
            'notification' => SocialProofNotification::class,
        ],

        'sell_tokens' => [
            'delay_days' => 30,
            'condition' => 'low_tokens',
            'notification' => SellTokensNotification::class,
        ],

    ],

];
