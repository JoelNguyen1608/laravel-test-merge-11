
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
            ->line('You are receiving this email because we received a password reset request for your account. Please use the link below to reset your password.')
            ->action('Reset Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('This password reset link will expire in '.$this->expiration.' minutes. If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser.')
            ->action('Reset Password', url(config('services.frontend_url').'/reset-password?token='.$this->token))
            ->line('If you did not request a password reset, no further action is required.');
    }
}
