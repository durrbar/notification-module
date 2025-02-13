<?php

namespace Modules\Notification\Notifications;

use Modules\Delivery\Models\Delivery;
use Modules\Notification\Notifications\BaseNotification;

class DeliveryCompleteNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'delivery';

    protected Delivery $delivery;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Delivery $delivery, $user)
    {
        $this->delivery = $delivery;
        $this->user = $user;
        parent::__construct($delivery, $user);
    }

    /**
     * Get the mail subject for the notification.
     */
    protected function getMailSubject(): string
    {
        return __('delivery::completed.subject');
    }

    /**
     * Get the mail greeting for the notification.
     */
    protected function getMailGreeting(): ?string
    {
        return __('delivery::completed.mail_greeting', [
            'name' => $this->user->name,
        ]);
    }

    /**
     * Generate the message text for database, mail, and SMS.
     */
    protected function getMessageText(): string
    {
        return __('delivery::completed.message', [
            'delivery' => $this->delivery->delivery_number,
            'order' => $this->delivery->order->order_number,
        ]);
    }

    /**
     * Get the mail content for the notification.
     */
    protected function getMailContent(): string
    {
        return $this->getMessageText();
    }

    /**
     * Get the mail action text for the notification.
     */
    protected function getMailActionText(): string
    {
        return __('delivery::completed.view_order_details');
    }

    /**
     * Get the mail action URL for the notification.
     */
    protected function getMailActionUrl(): string
    {
        return url('/delivery/' . $this->delivery->id);
    }

    /**
     * Get the mail footer for the notification.
     */
    protected function getMailFooter(): string
    {
        return __('delivery::completed.mail_footer');
    }

    /**
     * Get the mail salutation for the notification.
     */
    protected function getMailSalutation(): ?string
    {
        return __('delivery::completed.mail_salutation');
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
        return $this->getMessageText();
    }

    /**
     * Get the database title for the notification.
     */
    protected function getDatabaseTitle(): string
    {
        return __('delivery::completed.database_title');
    }

    /**
     * Get the database message for the notification.
     */
    protected function getDatabaseMessage(): string
    {
        return $this->getMessageText();
    }

    /**
     * Get the database URL for the notification.
     */
    protected function getDatabaseUrl(): string
    {
        return url('/delivery/' . $this->delivery->id);
    }

    /**
     * Get the avatar URL for the notification.
     */
    protected function getAvatarUrl(): ?string
    {
        return $this->user->avatar_url ?? null;
    }

    /**
     * Get the category for the notification.
     */
    protected function getCategory(): string
    {
        return __('delivery::completed.category');
    }
}
