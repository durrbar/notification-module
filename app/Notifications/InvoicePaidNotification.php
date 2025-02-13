<?php

namespace Modules\Notification\Notifications;

use Modules\Invoice\Models\Invoice;
use Modules\Notification\Notifications\BaseNotification;

class InvoicePaidNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'invoice';

    protected Invoice $invoice;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice, $user)
    {
        $this->invoice = $invoice;

        parent::__construct($invoice, $user);
    }

    /**
     * Get the mail subject for the notification.
     */
    protected function getMailSubject(): string
    {
        return __('notification::invoice.paid.subject', [], 'Your Invoice Has Been Paid');
    }

    /**
     * Get the mail greeting for the notification.
     */
    protected function getMailGreeting(): ?string
    {
        return __('notification::invoice.paid.mail_greeting', [
            'name' => $this->user->name,
        ]) ?? 'Hello,';
    }

    /**
     * Generate the message text for database, mail, and SMS.
     */
    protected function getMessageText(): string
    {
        return __('notification::invoice.paid.message', [
            'invoice' => $this->invoice->invoice_number,
            'order' => $this->invoice->order->order_number,
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
        return __('notification::invoice.paid.view_invoice', [], 'View Invoice');
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
        return __('notification::invoice.paid.mail_footer', [], 'Thank you for your business!');
    }

    /**
     * Get the mail salutation for the notification.
     */
    protected function getMailSalutation(): ?string
    {
        return __('notification::invoice.paid.mail_salutation', [], 'Best regards, The Team');
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
        return __('notification::invoice.paid.database_title', [], 'Invoice Paid');
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
        return url('/invoices/' . $this->invoice->id);
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
        return __('notification::invoice.paid.category', [], 'Payment');
    }
}
