<?php

namespace Manta\FluxCMS\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     */
    public string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('flux-cms.staff.reset-password', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Wachtwoord resetten - ' . config('app.name'))
            ->greeting('Hallo ' . $notifiable->name . ',')
            ->line('Je ontvangt deze e-mail omdat er een verzoek is gedaan om je wachtwoord te resetten.')
            ->action('Wachtwoord resetten', $url)
            ->line('Deze link is geldig voor ' . config('auth.passwords.staff.expire', 60) . ' minuten.')
            ->line('Als je geen verzoek hebt gedaan om je wachtwoord te resetten, hoef je niets te doen.')
            ->salutation('Met vriendelijke groet,<br>' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
