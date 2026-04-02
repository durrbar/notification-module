<?php

declare(strict_types=1);

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Notification\Policies\UserNotificationPreferencePolicy;
use Modules\User\Models\User;

// use Modules\Notification\Database\Factories\UserNotificationPreferenceFactory;

#[Fillable([
    'user_id',
    'type',
    'database',
    'email',
    'sms',
    'broadcast',
])]
#[UsePolicy(UserNotificationPreferencePolicy::class)]
class UserNotificationPreference extends Model
{
    use HasFactory;
    use HasUuids;

    // protected static function newFactory(): UserNotificationPreferenceFactory
    // {
    //     // return UserNotificationPreferenceFactory::new();
    // }

    /**
     * Get the user associated with the notification preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
