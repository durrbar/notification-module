<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Modules\Delivery\Models\Delivery;

class DeliveryCompleteNotification extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'delivery';

    protected Delivery $delivery;

    /**
     * Create a new notification instance.
     */
    public function __construct(Delivery $delivery, $user)
    {
        $this->delivery = $delivery;

        parent::__construct($delivery, $user);
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
        return __('notification::delivery.completed.category');
    }

    /**
     * Get the database message for the notification.
     */
    public function getDatabaseMessage(): string
    {
        return $this->getMessageText();
    }

    /**
     * Get the database title for the notification.
     */
    public function getDatabaseTitle(): string
    {
        return __('notification::delivery.completed.database_title');
    }

    /**
     * Get the database URL for the notification.
     */
    public function getDatabaseUrl(): string
    {
        return url('/delivery/'.$this->delivery->id);
    }

    /**
     * Get the mail subject for the notification.
     */
    public function getMailSubject(): string
    {
        return __('notification::delivery.completed.subject');
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
            ->action(
                __('notification::delivery.completed.view_order_details'),
                url('/delivery/'.$this->delivery->id)
            )
            ->line(__('notification::delivery.completed.mail_footer'))
            ->salutation(__('notification::delivery.completed.mail_salutation'));
    }

    /**
     * Generate the message text for database and SMS.
     */
    public function getMessageText(): string
    {
        return __('notification::delivery.completed.message', [
            'delivery' => $this->delivery->delivery_number,
            'order' => $this->delivery->order->order_number,
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
