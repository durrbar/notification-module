<?php
namespace Modules\Notification\Listeners;

use Modules\Delivery\Events\DeliveryScheduledEvent;
use Modules\Notification\Notifications\DeliveryScheduledNotification;
use Modules\Notification\Services\NotificationService;

class DeliveryScheduledListener
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(DeliveryScheduledEvent $event)
    {
        $order = $event->order;
        $user = $order->customer;

        // Send notification about the scheduled delivery
        $this->notificationService->sendNotification(
            $user,
            DeliveryScheduledNotification::class,
            $order
        );
    }
}
