<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Modules\Invoice\Models\Invoice;

class InvoiceCreatedNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'invoice';

    protected Invoice $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoice $invoice, $user)
    {
        $this->invoice = $invoice;

        parent::__construct($invoice, $user);
    }

    /**
     * Get the avatar URL for the notification.
     */
    public function getAvatarUrl(): ?string
    {
        return $this->user->avatar_url ?? null;
    }

    /**
     * Get the category for the notification.
     */
    public function getCategory(): string
    {
        return __('notification::invoice.created.category', [], 'Payment');
    }

    /**
     * Get the database title for the notification.
     */
    public function getDatabaseTitle(): string
    {
        return __('notification::invoice.created.database_title', [], 'Invoice Generated');
    }

    /**
     * Get the database message for the notification.
     */
    public function getDatabaseMessage(): string
    {
        return $this->getMessageText();
    }

    /**
     * Get the database URL for the notification.
     */
    public function getDatabaseUrl(): string
    {
        return url('/invoices/'.$this->invoice->id);
    }

    /**
     * Get the mail subject for the notification.
     */
    public function getMailSubject(): string
    {
        return __('notification::invoice.created.subject', [], 'Your Invoice Has Been Generated');
    }

    /**
     * Customize mail body (lines, action, etc.).
     */
    public function getMailBody(MailMessage $mail): void
    {
        $mail->greeting(__('notification::invoice.created.mail_greeting', ['name' => $this->user->name]) ?? 'Hello,')
            ->line($this->getMessageText())
            ->action(
                __('notification::invoice.created.view_invoice', [], 'View Invoice'),
                url('/invoices/'.$this->invoice->id)
            )
            ->line(__('notification::invoice.created.mail_footer', [], 'Thank you for your business!'))
            ->salutation(__('notification::invoice.created.mail_salutation', [], 'Best regards, The Team'));
    }

    /**
     * Generate the message text for database and SMS.
     */
    public function getMessageText(): string
    {
        return __('notification::invoice.created.message', [
            'invoice' => $this->invoice->invoice_number,
            'order' => $this->invoice->order->order_number,
        ]);
    }

    /**
     * Get the SMS message for the notification.
     */
    public function getSmsMessage(): string
    {
        return $this->getMessageText();
    }
}
