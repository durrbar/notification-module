<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications.{id}', function ($user, $id) {
    return (string) $user->id === (string) $id;
}, ['middleware' => ['auth:sanctum']]);

Broadcast::channel('store_notice.created.{userID}', function ($user, $userID) {
    return (string) $user->id === (string) $userID;
}, ['middleware' => ['auth:sanctum']]);

Broadcast::channel('order.created.{userID}', function ($user, $userID) {
    return (string) $user->id === (string) $userID;
}, ['middleware' => ['auth:sanctum']]);

Broadcast::channel(
    'message.created.{userID}',
    function ($user, $userID) {
        return (string) $user->id === (string) $userID;
    },
    ['middleware' => ['auth:sanctum']]
);
