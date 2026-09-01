<?php

namespace App\Notifications;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellTokensNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $step = 'sell_tokens',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Artist $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Comprá más tokens y seguí creando'))
            ->view('emails.onboarding.sell-tokens', [
                'artist' => $notifiable,
            ]);
    }

    public function toArray(Artist $notifiable): array
    {
        return [
            'step' => $this->step,
            'title' => __('Comprá más tokens y seguí creando'),
            'body' => __('Te quedan pocos tokens. Comprá un paquete y seguí registrando tus obras.'),
        ];
    }
}
