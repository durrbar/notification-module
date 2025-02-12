<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
});
