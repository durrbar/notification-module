<?php

declare(strict_types=1);

return [
    'name' => 'Notification',
    'dynamic_queue_enabled' => env('NOTIFICATION_DYNAMIC_QUEUE_ENABLED', true),
];
