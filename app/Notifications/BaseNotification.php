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

    /**
     * Default queue name for this notification.
     */
    // public string $queue = 'notifications';

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $data
     * @param  mixed  $user
     */
    public function __construct(mixed $data, mixed $user)
    {
        $this->data = $data;
        $this->user = $user;
        $this->notificationId = Str::uuid()->toString();
        $this->preferences = $this->getPreferences($user);
    }

    /* ------------------------------------------------------------------------
     |  Notification Channels
     | ------------------------------------------------------------------------ */

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     */
    final public function via(mixed $notifiable): array
    {
        return $this->resolveChannels();
    }

    /**
     * Set dynamic queue names for each notification channel.
     *
     * @return array<string, string>
     */
    // public function viaQueues(): ?array
    // {
    //     if (!config('notification.dynamic_queue_enabled')) {
    //         \Log::info('🔕 viaQueues disabled by config');
    //         return null; // Fallback to default queue
    //     }

    //     $type = static::NOTIFICATION_TYPE ?: Str::kebab(class_basename(static::class));

    //     $queues = [
    //         'mail' => "notifications-{$type}-mail",
    //         'database' => "notifications-{$type}-database",
    //         'broadcast' => "notifications-{$type}-broadcast",
    //         'sms' => "notifications-{$type}-sms",
    //     ];

    //     return $queues;
    // }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
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

    /**
     * Get the SMS representation of the notification.
     */
    final public function toSms(mixed $notifiable): void
    {
        // Example implementation (commented out):
        // return (new \Illuminate\Notifications\Messages\NexmoMessage)
        //     ->content($this->getSmsMessage());
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    final public function toDatabase(mixed $notifiable): array
    {
        return $this->notificationPayload();
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    final public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        // $type = static::NOTIFICATION_TYPE ?: Str::kebab(class_basename(static::class));

        return new BroadcastMessage($this->notificationPayload());
        // ->onQueue("notifications-{$type}-broadcast");
    }

    /**
     * Customize the data payload for the broadcast event.
     */
    final public function broadcastWith(): array
    {
        return $this->notificationPayload();
    }

    /**
     * Define the private channel the notification should broadcast on.
     */
    final public function broadcastOn(): array
    {
        return [new PrivateChannel('notifications.'.$this->user->id)];
    }

    /**
     * Customize the broadcast event name.
     */
    final public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
