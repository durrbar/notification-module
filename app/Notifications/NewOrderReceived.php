<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Modules\Order\Models\Order;

class NewOrderReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly Order $order,
        protected readonly string $receiver = 'storeOwner'
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        App::setLocale($this->order->language ?? DEFAULT_LANGUAGE);
        $customer = $this->order->customer?->name ?? 'Guest Customer';

        if ($this->receiver === 'admin') {
            $subject = __('notification::sms.order.orderCreated.admin.subject');
            $url = config('shop.dashboard_url').'/orders/'.$this->order->id;
        } else {
            $subject = __('notification::sms.order.orderCreated.storeOwner.subject');
            $url = config('shop.dashboard_url').$this->order->shop->slug.'/orders/'.$this->order->id;
        }

        return (new MailMessage())
            ->subject($subject)
            ->markdown('notification::emails.order.order-received', ['order' => $this->order, 'customer' => $customer, 'receiver' => $this->receiver, 'url' => $url]);
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
