<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Modules\Refund\Models\Refund;

class RefundUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly Refund $refund,
        protected readonly string $receiver = 'admin'
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $order = $this->refund->order;
        App::setLocale($order->language ?? DEFAULT_LANGUAGE);
        $status = ' **'.$this->refund->status.'** ';
        if ($this->receiver === 'admin') {
            $subject = __('notification::sms.order.refundStatusChange.admin.subject');
            $url = config('shop.dashboard_url').'/orders/'.$order->id;

            return (new MailMessage())
                ->subject($subject)
                ->markdown('notification::emails.refund.refund-updated', [
                    'order' => $order,
                    'refund' => $this->refund,
                    'status' => $status,
                    'url' => $url,
                    'receiver' => $this->receiver,
                ]);
        }
        $subject = __('notification::sms.order.refundStatusChange.customer.subject');
        $url = config('shop.dashboard_url').'/orders/'.$order->id;

        return (new MailMessage())
            ->subject($subject)
            ->markdown('notification::emails.refund.refund-updated', [
                'order' => $order,
                'refund' => $this->refund,
                'url' => $url,
                'status' => $status,
                'receiver' => $this->receiver,
            ]);
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
