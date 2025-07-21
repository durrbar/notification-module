<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Notifications\NewReviewCreated;
use Modules\Notification\Traits\SmsTrait;
use Modules\Review\Events\ReviewCreated;
use Modules\Vendor\Models\Shop;

class SendReviewNotification implements ShouldQueue
{
    use SmsTrait;

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(ReviewCreated $event)
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::REVIEW_CREATED, $event->review->language ?? DEFAULT_LANGUAGE);
        if ($emailReceiver['vendor']) {
            $shop_id = $event->review->shop_id;
            $shop = Shop::with('owner')->findOrFail($shop_id);
            $shop_owner = $shop->owner;
            $shop_owner->notify(new NewReviewCreated($event->review));
        }
    }
}
