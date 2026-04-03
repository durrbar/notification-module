<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Modules\Order\Models\Order;

class PaymentSuccessfulNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly Order $order) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        App::setLocale($this->order->language ?? DEFAULT_LANGUAGE);

        return (new MailMessage())
            ->subject(__('notification::sms.order.paymentSuccessOrder.admin.subject'))
            ->markdown(
                'notification::emails.payment.payment-successful',
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
