<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

interface NotificationContract
{
    /**
     * Get the avatar URL for the notification.
     */
    public function getAvatarUrl(): ?string;

    /**
     * Get the category for the notification.
     */
    public function getCategory(): string;

    /**
     * Get the message to be stored in the database.
     */
    public function getDatabaseMessage(): string;

    /**
     * Get the title for the notification (database).
     */
    public function getDatabaseTitle(): string;

    /**
     * Get the URL for the notification (database).
     */
    public function getDatabaseUrl(): string;

    /**
     * Customize mail body (lines, action, etc.).
     */
    public function getMailBody(MailMessage $mail): void;

    /**
     * Get the subject for the mail message.
     */
    public function getMailSubject(): string;

    /**
     * Generic message content used across channels.
     */
    public function getMessageText(): string;

    /**
     * Get the SMS message content.
     */
    public function getSmsMessage(): string;
}
