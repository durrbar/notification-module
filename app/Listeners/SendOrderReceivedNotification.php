<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\NewOrderReceived;
use Modules\Notification\Traits\SmsTrait;
use Modules\Order\Events\OrderReceived;

class SendOrderReceivedNotification implements ShouldQueue
{
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(OrderReceived $event)
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::OrderCreated->value, $event->order->language);
        if ($emailReceiver['vendor']) {
            $vendor = $event->order->shop->owner;
            $vendor->notify(new NewOrderReceived($event->order));
        }
    }
}
