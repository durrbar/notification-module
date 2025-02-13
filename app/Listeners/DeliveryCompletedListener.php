<?php

namespace Modules\Notification\Listeners;

use Modules\Delivery\Events\DeliveryCompletedEvent;
use Modules\Notification\Notifications\DeliveryCompleteNotification;
use Modules\Notification\Services\NotificationService;

class DeliveryCompletedListener
{
    /**
     * Create the event listener.
     */

     protected NotificationService $notificationService;

     public function __construct(NotificationService $notificationService)
     {
         $this->notificationService = $notificationService;
     }


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
