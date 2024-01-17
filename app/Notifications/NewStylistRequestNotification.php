
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class NewStylistRequestNotification extends Notification
{
    use Queueable;

    private $requestId;

    public function __construct($requestId)
    {
        $this->requestId = $requestId;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $requestUrl = URL::to('/stylist/request/' . $this->requestId);

        return (new MailMessage)
            ->subject('New Stylist Request Notification')
            ->greeting('Hello!')
            ->line('A new stylist request has been submitted.')
            ->action('View Request', $requestUrl)
            ->line('Please review the request at your earliest convenience.')
            ->line('Thank you for using our application!');
    }
}
