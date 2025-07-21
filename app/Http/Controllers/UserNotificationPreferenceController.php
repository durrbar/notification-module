<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Notification\Http\Requests\UserNotificationPreferenceRequest;
use Modules\Notification\Models\UserNotificationPreference;
use Modules\Notification\Repositories\UserNotificationPreferenceRepository;
use Modules\Notification\Resources\UserNotificationPreferenceResource;

class UserNotificationPreferenceController extends CoreController
{
    public function __construct(protected UserNotificationPreferenceRepository $repository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $preferences = $request->user()->notificationPreferences()->get();

        return UserNotificationPreferenceResource::collection($preferences);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserNotificationPreferenceRequest $request)
    {
        $data = $request->validated();

        $preference = $this->repository->storeOrUpdate($data);

        return new UserNotificationPreferenceResource($preference);
    }

    /**
     * Show the specified resource.
     */
    public function show(UserNotificationPreference $preference)
    {
        $this->authorize('view', $preference);

        return new UserNotificationPreferenceResource($preference);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserNotificationPreferenceRequest $request, UserNotificationPreference $preference)
    {
        $this->authorize('update', $preference);

        $updated = $this->repository->updatePreference($request->user(), $preference, $request->validated());

        return new UserNotificationPreferenceResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserNotificationPreference $preference)
    {
        $this->authorize('delete', $preference);

        $this->repository->deletePreference(Auth::user(), $preference);

        return response()->json(['message' => 'Notification preference deleted.'], Response::HTTP_NO_CONTENT);
    }
}
