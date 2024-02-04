<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewDeviceNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // You can add other channels like 'database', 'broadcast', etc.
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Fetch the user's IP address and location from the user model
        $userIpAddress = $notifiable->logs->last()->ip_address;
        $userIpLocation = $notifiable->logs->last()->ip_location;
    
        return (new MailMessage)
            ->line('A new device has been used to log in to your account.')
            ->line("IP Address: $userIpAddress")
            ->line("Location: $userIpLocation")
            ->action('View Activity', url('/'))
            ->line('If this was not you, please contact support.');
    }
}
