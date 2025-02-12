<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1/user')->group(function () {
    Route::controller(NotificationController::class)->prefix('notifications')->group(function () {
        Route::patch('mark-all-as-read', 'markAllAsRead');
        Route::delete('delete-all', 'deleteAll');
    });
    Route::apiResource('notifications', NotificationController::class)->names('notifications')->except('store');
});
