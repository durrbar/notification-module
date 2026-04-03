<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Modules\Invoice\Events\InvoicePaidEvent;
use Modules\Notification\Notifications\InvoicePaidNotification;
use Modules\Notification\Services\NotificationService;

class InvoicePaidListener
{
    public function __construct(private readonly NotificationService $notificationService) {}

    public function handle(InvoicePaidEvent $event): void
    {
        $invoice = $event->invoice;

        $this->notificationService->sendNotification(
            $invoice->order->customer,
            InvoicePaidNotification::class,
            $invoice
        );
    }
}
