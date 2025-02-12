<?php

namespace Modules\Notification\Notifications;

use Modules\Notification\Notifications\BaseNotification;
use Modules\Order\Models\Order;

class DeliveryScheduledNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'delivery';

    protected $order; // Explicitly define the order property for clarity

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $preferences)
    {
        // Cast $data to an Order object for clarity and type safety
        if (!$data instanceof Order) {
            throw new \InvalidArgumentException('The data must be an instance of Order.');
        }

        $this->order = $data; // Store the order explicitly
        parent::__construct($data, $preferences); // Pass $data to the parent class
    }

    /**
     * Get the mail subject for the notification.
     */
    protected function getMailSubject(): string
    {
        return 'Your Delivery Has Been Scheduled';
    }

    /**
     * Get the mail greeting for the notification.
     */
    protected function getMailGreeting(): ?string
    {
        return "Hello {$this->order->customer->name},";
    }

    /**
     * Get the mail content for the notification.
     */
    protected function getMailContent(): string
    {
        return "Your order #{$this->order->id} has been scheduled for delivery.";
    }

    /**
     * Get the mail action text for the notification.
     */
    protected function getMailActionText(): string
    {
        return 'View Order Details';
    }

    /**
     * Get the mail action URL for the notification.
     */
    protected function getMailActionUrl(): string
    {
        return url('/orders/' . $this->order->id);
    }

    /**
     * Get the mail footer for the notification.
     */
    protected function getMailFooter(): string
    {
        return 'Thank you for shopping with us!';
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
        return "Your delivery for order #{$this->order->id} has been scheduled.";
    }

    /**
     * Get the database title for the notification.
     */
    protected function getDatabaseTitle(): string
    {
        return 'Delivery Scheduled';
    }

    /**
     * Get the database message for the notification.
     */
    protected function getDatabaseMessage(): string
    {
        return "Your order #{$this->order->id} has been scheduled for delivery.";
    }

    /**
     * Get the database URL for the notification.
     */
    protected function getDatabaseUrl(): string
    {
        return url('/orders/' . $this->order->id);
    }

    /**
     * Get the avatar URL for the notification.
     */
    protected function getAvatarUrl(): ?string
    {
        return $this->order->customer->avatar_url ?? null; // Example: Fetch avatar URL from the customer model
    }

    /**
     * Get the category for the notification.
     */
    protected function getCategory(): string
    {
        return 'Order'; // Example: Category for this type of notification
    }
}
