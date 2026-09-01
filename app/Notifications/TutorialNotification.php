<?php

namespace App\Notifications;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TutorialNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $step = 'tutorial',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Artist $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('¿Necesitás ayuda? Mirá nuestro tutorial'))
            ->view('emails.onboarding.tutorial', [
                'artist' => $notifiable,
            ]);
    }

    public function toArray(Artist $notifiable): array
    {
        return [
            'step' => $this->step,
            'title' => __('¿Necesitás ayuda?'),
            'body' => __('Mirá nuestro tutorial paso a paso para crear tu primera obra.'),
        ];
    }
}
