<?php

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

    public $review;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = '';
        $product = Product::findOrFail($this->review->product_id);
        if ($product) {
            $url = config('shop.shop_url').'/products/'.$product->slug;
        }

        return (new MailMessage())
            ->markdown('notification::emails.review.created', ['review' => $this->review, 'url' => $url, 'product' => $product]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
