<?php

namespace Modules\Notification\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

abstract class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The name of the queue to which the notification job will be assigned.
     *
     * @var string
     */
    public $queue = 'notifications';

    /**
     * Notification type constant.
     */
    public const NOTIFICATION_TYPE = '';

    /**
     * Data payload for the notification.
     */
    protected $data;

    /**
     * The user receiving the notification.
     */
    protected $user;

    /**
     * Cached notification preferences.
     */
    protected $preferences;

    /**
     * Unique notification identifier.
     */
    protected string $notificationId;

    /**
     * Create a new notification instance.
     *
     * @param mixed $data
     * @param mixed $user
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
     * Determine which delivery channels to use for the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return array_filter([
            $this->isEmailChannelEnabled() ? 'mail' : null,
            $this->isSmsChannelEnabled() ? 'nexmo' : null,
            $this->isDatabaseChannelEnabled() ? 'database' : null,
            $this->isBroadcastChannelEnabled() ? 'broadcast' : null,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $mailMessage = (new MailMessage())->subject($this->getMailSubject());

        if ($greeting = $this->getMailGreeting()) {
            $mailMessage->greeting($greeting);
        }

        $mailMessage->line($this->getMailContent())
            ->action($this->getMailActionText(), $this->getMailActionUrl())
            ->line($this->getMailFooter());

        if ($salutation = $this->getMailSalutation()) {
            $mailMessage->salutation($salutation);
        }

        foreach ($this->getMailAttachments() as $attachment) {
            $mailMessage->attach($attachment['file'], $attachment['options'] ?? []);
        }

        if ($markdown = $this->getMailMarkdown()) {
            $mailMessage->markdown($markdown['view'], $markdown['data'] ?? []);
        }

        return $mailMessage;
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return void
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
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase($notifiable): array
    {
        return $this->notificationPayload();
    }

    /**
     * Get the broadcast representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->notificationPayload());
    }

    /**
     * Customize the data payload for the broadcast event.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return $this->notificationPayload();
    }

    /**
     * Define the private channel the notification should broadcast on.
     *
     * @return array
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('notifications.' . $this->user->id)];
    }

    /**
     * Customize the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }

    /* ------------------------------------------------------------------------
     |  Protected Helper Methods
     | ------------------------------------------------------------------------ */

    /**
     * Retrieve or create and cache the notification preferences for the user.
     *
     * @param mixed $user
     * @return object
     */
    protected function getPreferences($user): object
    {
        return Cache::remember("user_{$user->id}_preferences_" . static::NOTIFICATION_TYPE, 3600, function () use ($user) {
            return $user->notificationPreferences()->firstOrCreate(
                ['type' => static::NOTIFICATION_TYPE],
                [
                    'email'     => true,
                    'sms'       => false,
                    'broadcast' => true,
                ]
            );
        });
    }

    /**
     * Check if the email channel is enabled.
     *
     * @return bool
     */
    protected function isEmailChannelEnabled(): bool
    {
        return $this->preferences->email ?? false;
    }

    /**
     * Check if the SMS channel is enabled.
     *
     * @return bool
     */
    protected function isSmsChannelEnabled(): bool
    {
        return $this->preferences->sms ?? false;
    }

    /**
     * Check if the database channel is enabled.
     *
     * @return bool
     */
    protected function isDatabaseChannelEnabled(): bool
    {
        return true;
    }

    /**
     * Check if the broadcast channel is enabled.
     *
     * @return bool
     */
    protected function isBroadcastChannelEnabled(): bool
    {
        return $this->preferences->broadcast ?? false;
    }

    /**
     * Build and return the notification payload.
     *
     * @return array
     */
    protected function notificationPayload(): array
    {
        return [
            'id'         => $this->notificationId,
            'avatarUrl'  => $this->getAvatarUrl(),
            'type'       => static::NOTIFICATION_TYPE,
            'category'   => $this->getCategory(),
            'isUnRead'   => true,
            'createdAt'  => now()->toDateTimeString(),
            'title'      => $this->getDatabaseTitle(),
            'message'    => $this->getDatabaseMessage(),
            'url'        => $this->getDatabaseUrl(),
            'user_id'    => $this->user->id,
        ];
    }

    /**
     * Get the avatar URL for the notification.
     *
     * @return string|null
     */
    abstract protected function getAvatarUrl(): ?string;

    /**
     * Get the category for the notification.
     *
     * @return string
     */
    abstract protected function getCategory(): string;

    /**
     * Get the message to be stored in the database.
     *
     * @return string
     */
    abstract protected function getDatabaseMessage(): string;

    /**
     * Get the title for the notification (database).
     *
     * @return string
     */
    abstract protected function getDatabaseTitle(): string;

    /**
     * Get the URL for the notification (database).
     *
     * @return string
     */
    abstract protected function getDatabaseUrl(): string;

    /**
     * Get the text for the mail action button.
     *
     * @return string
     */
    abstract protected function getMailActionText(): string;

    /**
     * Get the URL for the mail action button.
     *
     * @return string
     */
    abstract protected function getMailActionUrl(): string;

    /**
     * Get the attachments for the mail message.
     *
     * @return array
     */
    abstract protected function getMailAttachments(): array;

    /**
     * Get the content for the mail message.
     *
     * @return string
     */
    abstract protected function getMailContent(): string;

    /**
     * Get the footer text for the mail message.
     *
     * @return string
     */
    abstract protected function getMailFooter(): string;

    /**
     * Get the greeting for the mail message.
     *
     * @return string|null
     */
    abstract protected function getMailGreeting(): ?string;

    /**
     * Get the markdown configuration for the mail message.
     *
     * @return array|null
     */
    abstract protected function getMailMarkdown(): ?array;

    /**
     * Get the salutation for the mail message.
     *
     * @return string|null
     */
    abstract protected function getMailSalutation(): ?string;

    /**
     * Get the subject for the mail message.
     *
     * @return string
     */
    abstract protected function getMailSubject(): string;

    /**
     * Get the SMS message content.
     *
     * @return string
     */
    abstract protected function getSmsMessage(): string;
}
