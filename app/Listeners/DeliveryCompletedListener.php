<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Notification\Notifications\DeliveryCompleteNotification;
use Modules\Notification\Services\NotificationService;

class DeliveryCompletedListener
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(DeliveryCompletedEvent $event): void
    {
        $delivery = $event->delivery;

        $this->notificationService->sendNotification(
            $delivery->order->customer,
            DeliveryCompleteNotification::class,
            $delivery
        );
    }
}
