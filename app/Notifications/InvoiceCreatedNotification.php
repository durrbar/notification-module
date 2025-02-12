<?php

namespace Modules\Notification\Notifications;

use Modules\Invoice\Models\Invoice;
use Modules\Notification\Notifications\BaseNotification;

class InvoiceCreatedNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'invoice';

    protected $invoice; // Explicitly define the invoice property for clarity

    /**
     * Create a new notification instance.
     */
    public function __construct($data, $preferences)
    {
        // Cast $data to an Invoice object for clarity and type safety
        if (!$data instanceof Invoice) {
            throw new \InvalidArgumentException('The data must be an instance of Invoice.');
        }

        $this->invoice = $data; // Store the invoice explicitly
        parent::__construct($data, $preferences); // Pass $data to the parent class
    }

    /**
     * Get the mail subject for the notification.
     */
    protected function getMailSubject(): string
    {
        return 'Your Invoice Has Been Generated';
    }

    /**
     * Get the mail greeting for the notification.
     */
    protected function getMailGreeting(): ?string
    {
        return "Hello {$this->invoice->order->customer->name},";
    }

    /**
     * Get the mail content for the notification.
     */
    protected function getMailContent(): string
    {
        return "Your invoice #{$this->invoice->id} has been generated for order #{$this->invoice->order->id}.";
    }

    /**
     * Get the mail action text for the notification.
     */
    protected function getMailActionText(): string
    {
        return 'View Invoice';
    }

    /**
     * Get the mail action URL for the notification.
     */
    protected function getMailActionUrl(): string
    {
        return url('/invoices/' . $this->invoice->id);
    }

    /**
     * Get the mail footer for the notification.
     */
    protected function getMailFooter(): string
    {
        return 'Thank you for your business!';
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
        // Example: Attach the invoice PDF
        return [
            [
                'file' => storage_path("app/invoices/{$this->invoice->id}.pdf"),
                'options' => [
                    'as' => "invoice_{$this->invoice->id}.pdf",
                    'mime' => 'application/pdf',
                ],
            ],
        ];
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
        return "Your invoice #{$this->invoice->id} has been generated for order #{$this->invoice->order->id}.";
    }

    /**
     * Get the database title for the notification.
     */
    protected function getDatabaseTitle(): string
    {
        return 'Invoice Generated';
    }

    /**
     * Get the database message for the notification.
     */
    protected function getDatabaseMessage(): string
    {
        return "Your invoice #{$this->invoice->id} has been generated for order #{$this->invoice->order->id}.";
    }

    /**
     * Get the database URL for the notification.
     */
    protected function getDatabaseUrl(): string
    {
        return url('/invoices/' . $this->invoice->id);
    }

    /**
     * Get the avatar URL for the notification.
     */
    protected function getAvatarUrl(): ?string
    {
        return $this->invoice->order->customer->avatar_url ?? null; // Example: Fetch avatar URL from the customer model
    }

    /**
     * Get the category for the notification.
     */
    protected function getCategory(): string
    {
        return 'Payment'; // Example: Category for this type of notification
    }
}
