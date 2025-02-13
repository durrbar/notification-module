<?php

namespace Modules\Notification\Listeners;

use Modules\Invoice\Events\InvoicePaidEvent;
use Modules\Notification\Notifications\InvoicePaidNotification;
use Modules\Notification\Services\NotificationService;

class InvoicePaidListener
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(InvoicePaidEvent $event): void
    {
        $invoice = $event->invoice;
        $user = $invoice->order->customer;

        // Send notification about the generated invoice
        $this->notificationService->sendNotification(
            $user,
            InvoicePaidNotification::class,
            $invoice
        );
    }
}
