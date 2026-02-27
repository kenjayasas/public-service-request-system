<?php

namespace App\Notifications;

use App\Models\ServiceRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $serviceRequest;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequest = $serviceRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $status = ucfirst(str_replace('_', ' ', $this->serviceRequest->status));
        
        return (new MailMessage)
            ->subject('Service Request Status Updated')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The status of your service request has been updated.')
            ->line('Request: ' . $this->serviceRequest->title)
            ->line('New Status: ' . $status)
            ->action('View Request', route('requests.show', $this->serviceRequest))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'request_id' => $this->serviceRequest->id,
            'request_title' => $this->serviceRequest->title,
            'old_status' => $this->serviceRequest->getOriginal('status'),
            'new_status' => $this->serviceRequest->status,
            'message' => 'Your request status has been updated to ' . 
                        ucfirst(str_replace('_', ' ', $this->serviceRequest->status))
        ];
    }
}