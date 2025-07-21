<?php

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Modules\Notification\Notifications\StoreNoticeNotification;
use Modules\Role\Enums\Permission;
use Modules\User\Models\User;
use Modules\Vendor\Events\StoreNoticeEvent;

class StoreNoticeListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(StoreNoticeEvent $event)
    {
        $users = User::whereHas('permissions', function (Builder $query): void {
            $query->whereIn('name', [Permission::SUPER_ADMIN]);
        })->get();

        if (! empty($users)) {
            foreach ($users as $user) {
                $user->notify(new StoreNoticeNotification($event->storeNotice, $event->action));
            }
        }
    }
}
