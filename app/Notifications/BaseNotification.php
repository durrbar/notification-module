<?php

namespace Modules\Notification\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Modules\Notification\Traits\BaseNotificationTrait;

abstract class BaseNotification extends Notification implements ShouldQueue, NotificationContract
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
    public function __construct($data, $user)
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
    public function via($notifiable): array
    {
        return $this->resolveChannels();
    }

    /**
     * Set dynamic queue names for each notification channel.
     */
    // public function viaQueues(): array
    // {
    //     $type = static::NOTIFICATION_TYPE ?: Str::kebab(class_basename(static::class));

    //     return [
    //         'mail' => "notifications.{$type}.mail",
    //         'database' => "notifications.{$type}.database",
    //         'broadcast' => "notifications.{$type}.broadcast",
    //         'nexmo' => "notifications.{$type}.sms",
    //     ];
    // }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): MailMessage
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
    public function toNexmo($notifiable)
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
    public function toDatabase($notifiable): array
    {
        return $this->notificationPayload();
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->notificationPayload());
    }

    /**
     * Customize the data payload for the broadcast event.
     */
    public function broadcastWith(): array
    {
        return $this->notificationPayload();
    }

    /**
     * Define the private channel the notification should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('notifications.' . $this->user->id)];
    }

    /**
     * Customize the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
