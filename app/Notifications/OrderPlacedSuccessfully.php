<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Dompdf\Options;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class OrderPlacedSuccessfully extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly array $invoiceData) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        App::setLocale($this->invoiceData['language'] ?? DEFAULT_LANGUAGE);
        $invoiceData = $this->invoiceData;
        $pdf = PDF::loadView('pdf.order-invoice', $invoiceData);
        $options = new Options();
        $options->setIsPhpEnabled(true);
        $options->setIsJavascriptEnabled(true);
        $pdf->getDomPDF()->setOptions($options);

        return (new MailMessage())
            ->subject(__('notification::sms.order.orderCreated.customer.subject'))
            ->markdown('notification::emails.order.order-invoice', $invoiceData)
            ->attachData($pdf->output(), 'invoice.pdf');
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
