<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Ecommerce\Models\Product;

class ProductRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly Product $product) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Product Rejected')
            ->markdown(
                'notification::emails.product.product-rejected',
                [
                    'name' => $this->product->name,
                    'url' => config('shop.shop_url').'/products/'.$this->product->slug.'/edit',
                ]
            );
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
