<?php

declare(strict_types=1);

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Role\Enums\Permission;
use Modules\Vendor\Models\StoreNotice;

class StoreNoticeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected readonly StoreNotice $storeNotice,
        protected readonly ?string $action
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $role = $this->storeNotice->creator->hasPermissionTo(Permission::SuperAdmin->value)
            ? 'Admin'
            : 'Shop Owner';

        return (new MailMessage())
            ->subject('Notice From '.$role.'.')
            ->markdown('notification::emails.storeNotice.storeNotice', [
                'notice' => $this->storeNotice,
                'action' => $this->action,
                'role' => $role,
            ]);
    }

    public function toArray(mixed $notifiable): array
    {
        return [];
    }
}
