<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Ecommerce\Models\Product;
use Modules\Review\Models\Review;

class NewReviewCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly Review $review) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $product = Product::findOrFail($this->review->product_id);
        $url = config('shop.shop_url').'/products/'.$product->slug;

        return (new MailMessage())
            ->markdown('notification::emails.review.created', ['review' => $this->review, 'url' => $url, 'product' => $product]);
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
