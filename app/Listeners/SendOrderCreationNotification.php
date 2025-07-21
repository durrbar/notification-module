<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\NewOrderReceived;
use Modules\Notification\Notifications\OrderPlacedSuccessfully;
use Modules\Notification\Traits\OrderSmsTrait;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Events\OrderCreated;

class SendOrderCreationNotification implements ShouldQueue
{
    use OrderSmsTrait;
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $customer = $event->order->customer;
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ORDER_CREATED, $order->language);
        if ($customer && $emailReceiver['customer'] && $order->parent_id == null) {
            $customer->notify(new OrderPlacedSuccessfully($event->invoiceData));
        }
        if ($emailReceiver['admin']) {
            $admins = $this->adminList();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderReceived($order, 'admin'));
            }
        }
        $this->sendOrderCreationSms($order);
    }
}
