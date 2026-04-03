<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notification\Notifications\StoreNoticeNotification;
use Modules\Role\Enums\Permission;
use Modules\User\Models\User;
use Modules\Vendor\Events\StoreNoticeEvent;

class StoreNoticeListener implements ShouldQueue
{
    public function handle(StoreNoticeEvent $event): void
    {
        $users = User::whereHas('permissions', function (Builder $query): void {
            $query->whereIn('name', [Permission::SuperAdmin->value]);
        })->get();

        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                $user->notify(new StoreNoticeNotification($event->storeNotice, $event->action));
            }
        }
    }
}
