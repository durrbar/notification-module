<?php

declare(strict_types=1);

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

    public function handle(ReviewCreated $event): void
    {
        $emailReceiver = $this->getWhichUserWillGetEmail(EventType::ReviewCreated->value, $event->review->language ?? DEFAULT_LANGUAGE);
        if ($emailReceiver['vendor']) {
            $shopId = $event->review->shop_id;
            $shop = Shop::with('owner')->findOrFail($shopId);
            $shop->owner->notify(new NewReviewCreated($event->review));
        }
    }
}
