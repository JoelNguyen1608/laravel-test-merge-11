
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class StylistPasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $expiration;

    public function __construct($token, $expiration)
    {
        $this->token = $token;
        $this->expiration = $expiration;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = URL::to('/password-reset/' . $this->token);

        return (new MailMessage)
            ->subject('Password Reset Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('This password reset link will expire in 60 minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
