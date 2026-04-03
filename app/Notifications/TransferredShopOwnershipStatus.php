<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\User\Models\User;
use Modules\Vendor\Models\Shop;

class TransferredShopOwnershipStatus extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly Shop $shop,
        protected readonly User $previousOwner,
        protected readonly User $newOwner,
        protected readonly ?array $optional = null
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $url = config('shop.dashboard_url')."/{$this->shop->slug}";
        $shopName = $this->shop->name;
        $newOwnerName = $this->newOwner->name;
        $previousOwnerName = $this->previousOwner->name;

        return (new MailMessage())
            ->subject(APP_NOTICE_DOMAIN.' Shop Ownership Reminder')
            ->markdown(
                'notification::emails.ownership.status',
                [
                    'shopName' => $shopName,
                    'newOwnerName' => $newOwnerName,
                    'previousOwnerName' => $previousOwnerName,
                    'url' => $url,
                    'message' => $this->optional['message'],
                ]
            );
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
