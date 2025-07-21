<?php

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

    protected $storeNotice;

    protected $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StoreNotice $storeNotice, ?string $action)
    {
        $this->storeNotice = $storeNotice;
        $this->action = $action;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->storeNotice->creator->hasPermissionTo(Permission::SUPER_ADMIN)) {
            $role = 'Admin';
        } else {
            $role = 'Shop Owner';
        }

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
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
