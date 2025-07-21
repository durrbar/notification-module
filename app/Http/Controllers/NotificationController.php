<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Modules\Notification\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = $request->user()->notifications()->paginate(10);

        return response()->json(['notifications' => NotificationResource::collection($notifications->items())]);
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return $this->respondNotFound('Notification not found.');
        }

        return response()->json(['data' => new NotificationResource($notification)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|uuid', // or 'integer' if using numeric IDs
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid notification ID.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return $this->respondNotFound('Notification not found.');
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return $this->respondSuccess('Notification marked as read.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if (! $notification) {
            return $this->respondNotFound('Notification not found.');
        }

        $notification->delete();

        return $this->respondSuccess('Notification deleted successfully.');
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $unreadNotifications = $request->user()->unreadNotifications;

        if ($unreadNotifications->isEmpty()) {
            return $this->respondSuccess('No unread notifications to mark as read.');
        }

        $unreadNotifications->markAsRead();

        return $this->respondSuccess('All notifications marked as read.');
    }

    /**
     * Delete all notifications for the authenticated user.
     */
    public function deleteAll(Request $request): JsonResponse
    {
        $request->user()->notifications()->delete();

        return $this->respondSuccess('All notifications deleted successfully.');
    }

    /**
     * Respond with a success message.
     */
    private function respondSuccess(string $message): JsonResponse
    {
        return response()->json(['message' => $message], Response::HTTP_OK);
    }

    /**
     * Respond with a not found error.
     */
    private function respondNotFound(string $message = 'Resource not found.'): JsonResponse
    {
        return response()->json(['message' => $message], Response::HTTP_NOT_FOUND);
    }
}
