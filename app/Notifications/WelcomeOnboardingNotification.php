<?php

namespace App\Notifications;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeOnboardingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $step = 'welcome',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Artist $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('¡Bienvenido a QRTE!'))
            ->view('emails.onboarding.welcome', [
                'artist' => $notifiable,
            ]);
    }

    public function toArray(Artist $notifiable): array
    {
        return [
            'step' => $this->step,
            'title' => __('¡Bienvenido a QRTE!'),
            'body' => __('Tu cuenta está lista. Creá tu primera obra y obtené tu QR permanente.'),
        ];
    }
}
