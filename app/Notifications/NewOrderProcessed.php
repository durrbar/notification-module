<?php

namespace Modules\Notification\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Order\Models\Order;

class NewOrderProcessed extends BaseNotification
{
    public const NOTIFICATION_TYPE = 'order';

    protected Order $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order, $user)
    {
        $this->order = $order;

        parent::__construct($order, $user);
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
        return url('/order/'.$this->order->id);
    }

    /**
     * Get the mail subject for the notification.
     */
    public function getMailSubject(): string
    {
        return 'New Order is Processed!';
    }

    /**
     * Customize mail body (lines, action, etc.).
     */
    public function getMailBody(MailMessage $mail): void
    {
        $mail->markdown(
            'notification::emails.order.order-processed',
            [
                'order' => $this->order,
                'url' => config('shop.dashboard_url').$this->order->shop->slug.'/orders/'.$this->order->id,
            ]
        );
    }

    /**
     * Generate the message text for database and SMS.
     */
    public function getMessageText(): string
    {
        return __('notification::order.processed.message', [
            'delivery' => $this->order->delivery->delivery_number,
            'order' => $this->order->order_number,
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
