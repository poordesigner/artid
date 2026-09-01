<?php

namespace App\Notifications;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SocialProofNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $step = 'social_proof',
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(Artist $notifiable): MailMessage
    {
        $count = Artist::whereHas('artworks')->count();

        return (new MailMessage)
            ->subject(__('Artistas como vos ya crearon :count obras', ['count' => $count]))
            ->view('emails.onboarding.social-proof', [
                'artist' => $notifiable,
                'artistCount' => $count,
            ]);
    }

    public function toArray(Artist $notifiable): array
    {
        $count = Artist::whereHas('artworks')->count();

        return [
            'step' => $this->step,
            'title' => __('Artistas como vos ya crearon :count obras', ['count' => $count]),
            'body' => __('Unite a la comunidad de artistas que ya tienen su identidad digital.'),
        ];
    }
}
