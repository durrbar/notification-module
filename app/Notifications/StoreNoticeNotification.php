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

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected readonly StoreNotice $storeNotice,
        protected readonly ?string $action
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
