<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\OrderStatusChangedNotification;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Events\OrderStatusChanged;
use Modules\User\Models\User;

class SendOrderStatusChangedNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderStatusChanged $event)
    {

        $order = $event->order;
        $customer = $event->order->customer;

        $this->sendOrderStatusChangeSms($order);
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_STATUS_CHANGED, $order->language ?? DEFAULT_LANGUAGE);
        if ($emailReceiver['vendor'] && $order->parent_id != null) {
            $vendor_id = $order->shop->owner_id;
            $vendor = User::find($vendor_id);

            if ($vendor) {
                $vendor->notify(new OrderStatusChangedNotification($event->order));
            }
        }
        if ($emailReceiver['customer'] && $order->parent_id == null) {
            $customer->notify(new OrderStatusChangedNotification($event->order));
        }
        if ($emailReceiver['admin']) {
            $admins = $this->adminList();
            foreach ($admins as $admin) {
                $admin->notify(new OrderStatusChangedNotification($order));
            }
        }
    }
}
