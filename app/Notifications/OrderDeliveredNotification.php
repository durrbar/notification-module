<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Models\Order;

class OrderDeliveredNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SmsTrait;

    public function __construct(protected readonly Order $order) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Order was delivered')
            ->markdown(
                'notification::emails.order.order-delivered',
                [
                    'order' => $this->order,
                    'url' => config('shop.shop_url').'/orders/'.$this->order->tracking_number,
                ]
            );
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
