<?php

namespace Modules\Notification\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\OrderCancelledNotification;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Events\OrderCancelled;
use Modules\User\Models\User;

class SendOrderCancelledNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderCancelled $event)
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_CANCELLED, $event->order->language);
        if ($emailReceiver['customer'] && $event->order->customer && $event->order->parent_id == null) {
            $event->order->customer->notify(new OrderCancelledNotification($event->order));
        }
        if ($emailReceiver['vendor']) {
            if ($event->order->parent_id == null) {

                foreach ($event->order->children as $child_order) {
                    try {
                        $vendor_id = $child_order->shop->owner_id;
                        $vendor = User::find($vendor_id);
                        $vendor->notify(new OrderCancelledNotification($event->order));
                    } catch (Exception $exception) {
                        // Log::error($exception->getMessage());
                    }
                }
            } else {
                try {
                    $vendor_id = $event->order->shop->owner_id;
                    $vendor = User::find($vendor_id);
                    $vendor->notify(new OrderCancelledNotification($event->order));
                } catch (Exception $exception) {
                    //
                }
            }
        }
        if ($emailReceiver['admin']) {
            $admins = $this->adminList();
            foreach ($admins as $admin) {

                $admin->notify(new OrderCancelledNotification($event->order));
            }
        }
        $this->sendOrderCancelSms($event->order);
    }
}
