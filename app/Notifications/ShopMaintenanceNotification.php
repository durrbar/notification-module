<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopMaintenanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly mixed $shop,
        protected readonly mixed $body,
        protected readonly mixed $message
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $url = config('shop.dashboard_url');
        return (new MailMessage())
            ->subject(APP_NOTICE_DOMAIN.' Shop Maintenance Reminder')
            ->priority(1)
            ->markdown(
                'notification::emails.maintenance.shop-maintenance',
                [
                    'message' => $this->message,
                    'body' => $this->body,
                    'url' => $url.'/'.$this->shop->slug,
                ]
            );
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
