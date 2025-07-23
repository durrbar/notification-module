<?php

namespace Modules\Notification\Traits;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Cache;
use Modules\User\Models\User;

trait BaseNotificationTrait
{
    /**
     * Notification type identifier.
     *
     * @var string
     */
    public const string NOTIFICATION_TYPE = '';

    /**
     * The notification's primary data payload.
     */
    protected mixed $data;

    /**
     * The notifiable user.
     */
    protected mixed $user;

    /**
     * Cached preferences for notification delivery.
     */
    protected object $preferences;

    /**
     * Unique identifier for this notification instance.
     */
    protected string $notificationId;

    /* ------------------------------------------------------------------------
     |  Protected Helper Methods
     | ------------------------------------------------------------------------ */

    /**
     * Retrieve or create and cache the notification preferences for the user.
     *
     * @param  User  $user
     */
    protected function getPreferences($user): object
    {
        return Cache::flexible("user_{$user->id}_preferences_".static::NOTIFICATION_TYPE, [3540, 3600], function () use ($user) {
            return $user->notificationPreferences()->firstOrCreate(
                ['type' => static::NOTIFICATION_TYPE],
                [
                    'database' => true,
                    'email' => true,
                    'sms' => false,
                    'broadcast' => true,
                ]
            );
        });
    }

    /**
     * Resolve the notification's delivery channels.
     */
    protected function resolveChannels(): array
    {
        $channels = array_filter([
            $this->isEmailChannelEnabled() ? 'mail' : null,
            $this->isSmsChannelEnabled() ? 'nexmo' : null,
            $this->isDatabaseChannelEnabled() ? 'database' : null,
            $this->isBroadcastChannelEnabled() ? 'broadcast' : null,
        ]);

        return $channels ?: ['database'];
    }

    /**
     * Whether email channel is enabled.
     */
    protected function isEmailChannelEnabled(): bool
    {
        return $this->preferences->email ?? false;
    }

    /**
     * Whether SMS channel is enabled.
     */
    protected function isSmsChannelEnabled(): bool
    {
        return $this->preferences->sms ?? false;
    }

    /**
     * Whether database channel is enabled.
     */
    protected function isDatabaseChannelEnabled(): bool
    {
        return $this->preferences->database ?? false;
    }

    /**
     * Whether broadcast channel is enabled.
     */
    protected function isBroadcastChannelEnabled(): bool
    {
        return $this->preferences->broadcast ?? false;
    }

    /**
     * Structure of notification payload.
     */
    protected function notificationPayload(): array
    {
        return [
            'id' => $this->notificationId,
            'avatarUrl' => $this->getAvatarUrl(),
            'type' => static::NOTIFICATION_TYPE,
            'category' => $this->getCategory(),
            'isUnRead' => true,
            'createdAt' => now()->toDateTimeString(),
            'title' => $this->getDatabaseTitle(),
            'message' => $this->getDatabaseMessage(),
            'url' => $this->getDatabaseUrl(),
            'user_id' => $this->user->id,
        ];
    }

    /**
     * Get the attachments for the mail message.
     */
    public function getMailAttachments(): array
    {
        return [];
    }

    /**
     * Customize additional mail headers like from/replyTo.
     */
    public function getMailExtraHeader(MailMessage $mail): void
    {
    }
}
