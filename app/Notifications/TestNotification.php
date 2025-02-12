<?php

namespace Modules\Notification\Notifications;

class TestNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'order';

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $preferences)
    {
        parent::__construct($data, $preferences);
    }

    /**
     * Get the mail subject for the notification.
     */
    protected function getMailSubject(): string
    {
        return 'This is a Test Notification';
    }

    /**
     * Get the mail greeting for the notification.
     */
    protected function getMailGreeting(): ?string
    {
        return "Hello {$this->data->name},";
    }

    /**
     * Get the mail content for the notification.
     */
    protected function getMailContent(): string
    {
        return "This is a test notification sent to {$this->data->email}.";
    }

    /**
     * Get the mail action text for the notification.
     */
    protected function getMailActionText(): string
    {
        return 'View Details';
    }

    /**
     * Get the mail action URL for the notification.
     */
    protected function getMailActionUrl(): string
    {
        return url('/notifications');
    }

    /**
     * Get the mail footer for the notification.
     */
    protected function getMailFooter(): string
    {
        return 'Thank you for using our application!';
    }

    /**
     * Get the mail salutation for the notification.
     */
    protected function getMailSalutation(): ?string
    {
        return 'Best regards, The Team';
    }

    /**
     * Get the mail attachments for the notification.
     */
    protected function getMailAttachments(): array
    {
        return [];
    }

    /**
     * Get the mail Markdown content for the notification.
     */
    protected function getMailMarkdown(): ?array
    {
        return null; // Use default MailMessage rendering
    }

    /**
     * Get the SMS message for the notification.
     */
    protected function getSmsMessage(): string
    {
        return "This is a test notification sent to {$this->data->phone}.";
    }

    /**
     * Get the database title for the notification.
     */
    protected function getDatabaseTitle(): string
    {
        return 'Test Notification';
    }

    /**
     * Get the database message for the notification.
     */
    protected function getDatabaseMessage(): string
    {
        return "This is a test notification sent to {$this->data->email}.";
    }

    /**
     * Get the database URL for the notification.
     */
    protected function getDatabaseUrl(): string
    {
        return url('/notifications');
    }

    /**
     * Get the avatar URL for the notification.
     */
    protected function getAvatarUrl(): ?string
    {
        return $this->data->avatar_url ?? null;
    }

    /**
     * Get the category for the notification.
     */
    protected function getCategory(): string
    {
        return 'Order';
    }
}
