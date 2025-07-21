<?php

namespace Modules\Notification\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Repositories\BaseRepository;
use Modules\Notification\Models\UserNotificationPreference;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

class UserNotificationPreferenceRepository extends BaseRepository
{
    /**
     * Searchable fields for filtering via criteria.
     */
    protected $fieldSearchable = [
        'user_id',
        'type',
        'email',
        'sms',
        'broadcast',
        'database',
    ];

    /**
     * Boot repository with criteria if needed.
     */
    public function boot(): void
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    /**
     * Return model class name.
     */
    public function model(): string
    {
        return UserNotificationPreference::class;
    }

    /**
     * Get all preferences for a given user.
     */
    public function allForUser(string $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * Store or update a notification preference by type and user.
     */
    public function storeOrUpdate(array $data): UserNotificationPreference
    {
        return $this->model->updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'type' => $data['type'],
            ],
            $data
        );
    }

    /**
     * Update a notification preference (with authorization).
     */
    public function updatePreference($user, UserNotificationPreference $preference, array $data): UserNotificationPreference
    {
        $preference->update($data);

        // Optional: event(new UserNotificationPreferenceUpdated($preference));

        return $preference;
    }

    /**
     * Delete a preference (with authorization).
     */
    public function deletePreference($user, UserNotificationPreference $preference): bool
    {
        return $preference->delete();
    }

    /**
     * Enable a single channel for a user/type.
     */
    public function enableChannel(string $userId, string $type, string $channel): bool
    {
        return $this->model->where('user_id', $userId)->where('type', $type)->update([
            $channel => true,
        ]);
    }

    /**
     * Disable all channels for a specific type.
     */
    public function disableAllChannels(string $userId, string $type): bool
    {
        return $this->model->where('user_id', $userId)->where('type', $type)->update([
            'email' => false,
            'sms' => false,
            'broadcast' => false,
            'database' => false,
        ]);
    }

    /**
     * Get a specific preference by ID and user.
     */
    public function findByUser(string $userId, string $id): UserNotificationPreference
    {
        $preference = $this->model->where('id', $id)->where('user_id', $userId)->first();

        if (! $preference) {
            throw new ModelNotFoundException('Preference not found.');
        }

        return $preference;
    }
}
