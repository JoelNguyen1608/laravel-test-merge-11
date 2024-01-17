
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
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
        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('This password reset link will expire in '.$this->expiration.' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
