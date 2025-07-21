<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

// use Modules\Notification\Database\Factories\UserNotificationPreferenceFactory;

class UserNotificationPreference extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'database',
        'email',
        'sms',
        'broadcast',
    ];

    // protected static function newFactory(): UserNotificationPreferenceFactory
    // {
    //     // return UserNotificationPreferenceFactory::new();
    // }

    /**
     * Get the user associated with the notification preference.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
