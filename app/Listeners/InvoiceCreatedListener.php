<?php

namespace Modules\Notification\Listeners;

use Modules\Invoice\Events\InvoiceCreatedEvent;
use Modules\Notification\Services\NotificationService;
use Modules\Notification\Notifications\InvoiceCreatedNotification;

class InvoiceCreatedListener
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(InvoiceCreatedEvent $event)
    {
        $invoice = $event->invoice;
        $user = $invoice->order->customer;

        // Send notification about the generated invoice
        $this->notificationService->sendNotification(
            $user,
            InvoiceCreatedNotification::class,
            $invoice
        );
    }
}
