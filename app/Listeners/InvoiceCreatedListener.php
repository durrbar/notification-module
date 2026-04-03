<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Invoice\Events\InvoiceCreatedEvent;
use Modules\Notification\Notifications\InvoiceCreatedNotification;
use Modules\Notification\Services\NotificationService;

class InvoiceCreatedListener
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(InvoiceCreatedEvent $event): void
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
