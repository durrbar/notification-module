<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Delivery\Events\DeliveryScheduledEvent;
use Modules\Notification\Notifications\DeliveryScheduledNotification;
use Modules\Notification\Services\NotificationService;

class DeliveryScheduledListener
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(DeliveryScheduledEvent $event): void
    {
        $delivery = $event->delivery;

        $this->notificationService->sendNotification(
            $delivery->order->customer,
            DeliveryScheduledNotification::class,
            $delivery
        );
    }
}
