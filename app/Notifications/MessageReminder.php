<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly mixed $participant) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $prefix = $this->participant->type === 'user' ? 'message' : 'shop-message';
        $url = config('shop.dashboard_url').'/'.$prefix.'/'.$this->participant->conversation_id;

        return (new MailMessage())
            ->markdown('notification::emails.message.reminder', ['participant' => $this->participant, 'url' => $url]);
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
