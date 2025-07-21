<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\PaymentSuccessfulNotification;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Payment\Events\PaymentSuccess;
use Modules\User\Models\User;

class SendPaymentSuccessNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(PaymentSuccess $event)
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_PAYMENT_SUCCESS, $event->order->language ?? DEFAULT_LANGUAGE);
        if ($emailReceiver['vendor']) {
            foreach ($event->order->children as $child_order) {
                $vendor_id = $child_order->shop->owner_id;
                $vendor = User::findOrFail($vendor_id);
                $vendor->notify(new PaymentSuccessfulNotification($event->order));
            }
        }

        $customer = $event->order->customer;
        if (isset($customer) && $emailReceiver['customer']) {
            $customer->notify(new PaymentSuccessfulNotification($event->order));
        }

        $this->sendPaymentDoneSuccessfullySms($event->order);
    }
}
