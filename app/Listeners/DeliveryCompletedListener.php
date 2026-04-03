<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Notification\Notifications\DeliveryCompleteNotification;
use Modules\Notification\Services\NotificationService;

class DeliveryCompletedListener
{
    public function __construct(private NotificationService $notificationService) {}

    /**
     * Handle the event.
     */
    public function handle(DeliveryCompletedEvent $event): void
    {
        $delivery = $event->delivery;
        $user = $delivery->order->customer;

        // Send notification about the completed delivery
        $this->notificationService->sendNotification(
            $user,
            DeliveryCompleteNotification::class,
            $delivery
        );
    }
}
