<?php

namespace Modules\Notification\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\OrderDeliveredNotification;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Events\OrderDelivered;
use Modules\User\Models\User;

class SendOrderDeliveredNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderDelivered $event)
    {

        $order = $event->order;
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_DELIVERED, $order->language);
        if ($emailReceiver['customer'] && $order->customer && $order->parent_id == null) {
            $order->customer->notify(new OrderDeliveredNotification($order));
        }
        if ($emailReceiver['vendor']) {
            if ($order->parent_id) {
                try {
                    $vendor_id = $order->shop->owner_id;
                    $vendor = User::findOrFail($vendor_id);
                    $vendor->notify(new OrderDeliveredNotification($order));
                } catch (Exception $exception) {
                    //
                }
            }
        }
        if ($emailReceiver['admin']) {
            $admins = $this->adminList();
            foreach ($admins as $admin) {
                $admin->notify(new OrderDeliveredNotification($order));
            }
        }
        $this->sendOrderDeliveredSms($order);
    }
}
