<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Modules\Notification\Traits\BaseNotificationTrait;

abstract class BaseNotification extends Notification implements NotificationContract, ShouldQueue
{
    use BaseNotificationTrait;
    use Queueable;

    public function __construct(mixed $data, mixed $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->notificationId = Str::uuid()->toString();
        $this->preferences = $this->getPreferences($user);
    }

    final public function via(mixed $notifiable): array
    {
        return $this->resolveChannels();
    }

    final public function toMail(mixed $notifiable): MailMessage
    {
        $mail = new MailMessage();

        $mail->subject($this->getMailSubject());

        if ($attachments = $this->getMailAttachments()) {
            foreach ($attachments as $attachment) {
                $mail->attach($attachment['file'], $attachment['options'] ?? []);
            }
        }

        $this->getMailExtraHeader($mail);
        $this->getMailBody($mail);

        return $mail;
    }

    final public function toSms(mixed $notifiable): void
    {
        //
    }

    final public function toDatabase(mixed $notifiable): array
    {
        return $this->notificationPayload();
    }

    final public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->notificationPayload());
    }

    final public function broadcastWith(): array
    {
        return $this->notificationPayload();
    }

    final public function broadcastOn(): array
    {
        return [new PrivateChannel('notifications.'.$this->user->id)];
    }

    final public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
