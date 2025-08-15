<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class TestNotification extends BaseNotification
{
    public const string NOTIFICATION_TYPE = 'order';

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $preferences)
    {
        parent::__construct($data, $preferences);
    }

    /**
     * Get the avatar URL for the notification.
     */
    public function getAvatarUrl(): ?string
    {
        return $this->data->avatar_url ?? null;
    }

    /**
     * Get the category for the notification.
     */
    public function getCategory(): string
    {
        return 'Order';
    }

    /**
     * Get the database message for the notification.
     */
    public function getDatabaseMessage(): string
    {
        return "This is a test notification sent to {$this->data->email}.";
    }

    /**
     * Get the database title for the notification.
     */
    public function getDatabaseTitle(): string
    {
        return 'Test Notification';
    }

    /**
     * Get the database URL for the notification.
     */
    public function getDatabaseUrl(): string
    {
        return url('/notifications');
    }

    /**
     * Get the mail subject for the notification.
     */
    public function getMailSubject(): string
    {
        return 'This is a Test Notification';
    }

    /**
     * Customize mail body (lines, action, etc.).
     */
    public function getMailBody(MailMessage $mail): void
    {
        $mail->greeting(__('notification::delivery.completed.mail_greeting', [
            'name' => $this->user->name,
        ]))
            ->line($this->getMessageText())
            ->line(
                __('delivery.completed.mail_greeting', [
            'name' => $this->user->name,
        ])
            )
            ->action(
                __('notification::delivery.completed.view_order_details'),
                url('/delivery/')
            )
            ->line(__('notification::delivery.completed.mail_footer'))
            ->salutation(__('notification::delivery.completed.mail_salutation'));
    }

    /**
     * Generate the message text for database and SMS.
     */
    public function getMessageText(): string
    {
        return "This is a test notification sent to {$this->data->phone}.";
    }

    /**
     * Get the SMS message for the notification.
     */
    public function getSmsMessage(): string
    {
        return $this->getMessageText();
    }
}
