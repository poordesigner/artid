<?php

namespace App\Notifications;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderTokensNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $step = 'reminder_tokens',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Artist $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Todavía tenés tokens gratis'))
            ->view('emails.onboarding.reminder-tokens', [
                'artist' => $notifiable,
            ]);
    }

    public function toArray(Artist $notifiable): array
    {
        return [
            'step' => $this->step,
            'title' => __('Todavía tenés tokens gratis'),
            'body' => __('Tenés tokens disponibles para crear obras. ¡No los dejes pasar!'),
        ];
    }
}
