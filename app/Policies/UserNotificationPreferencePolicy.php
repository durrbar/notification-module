<?php

namespace Modules\Notification\Policies;

use Modules\Notification\Models\UserNotificationPreference;
use Modules\User\Models\User;

class UserNotificationPreferencePolicy
{
    public function view(User $user, UserNotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    public function update(User $user, UserNotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }

    public function delete(User $user, UserNotificationPreference $preference): bool
    {
        return $user->id === $preference->user_id;
    }
}
