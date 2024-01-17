
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class StylistRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $userId;
    protected $requestId;

    public function __construct($userId, $requestId)
    {
        $this->userId = $userId;
        $this->requestId = $requestId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Stylist Request')
            ->greeting('Hello!')
            ->line('A new stylist request has been made with the following details:')
            ->line('Request ID: ' . $this->requestId)
            ->action('View Request', url('/stylist/requests/' . $this->requestId))
            ->line('Thank you for using our application!');
    }
}
