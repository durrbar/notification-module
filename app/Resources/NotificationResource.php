<?php

namespace Modules\Notification\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->data['type'],
            'title' => $this->data['title'],
            'category' => $this->data['category'],
            'isUnRead' => is_null($this->read_at),
            'avatarUrl' => $this->data['avatarUrl'] || null,
            'createdAt' => $this->data['createdAt'],
        ];
    }
}
