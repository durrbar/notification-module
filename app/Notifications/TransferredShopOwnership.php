<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\User\Models\User;
use Modules\Vendor\Models\Shop;

class TransferredShopOwnership extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Shop $shop,
        public User $previousOwner,
        public User $newOwner,
        public ?array $optional = null
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        $url = config('shop.dashboard_url')."/{$this->shop->slug}";
        $shopName = $this->shop->name;
        $newOwnerName = $this->newOwner->name;
        $previousOwnerName = $this->previousOwner->name;

        return (new MailMessage())
            ->subject(APP_NOTICE_DOMAIN.' Shop Ownership Reminder')
            ->markdown(
                'notification::emails.ownership.reminder',
                [
                    'shopName' => $shopName,
                    'newOwnerName' => $newOwnerName,
                    'previousOwnerName' => $previousOwnerName,
                    'url' => $url,
                    'message' => $this->optional['message'],
                ]
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
