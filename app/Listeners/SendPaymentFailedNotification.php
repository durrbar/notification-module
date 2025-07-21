<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\PaymentFailedNotification;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Payment\Events\PaymentFailed;
use Modules\User\Models\User;

class SendPaymentFailedNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(PaymentFailed $event)
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_PAYMENT_FAILED, $event->order->language ?? DEFAULT_LANGUAGE);
        if ($emailReceiver['vendor']) {
            foreach ($event->order->children as $child_order) {
                $vendor_id = $child_order->shop->owner_id;
                $vendor = User::findOrFail($vendor_id);
                $vendor->notify(new PaymentFailedNotification($event->order));
            }
        }

        if ($emailReceiver['customer']) {
            $customer = $event->order->customer;
            $customer?->notify(new PaymentFailedNotification($event->order));
        }
        $this->sendPaymentFailedSms($event->order);
    }
}
