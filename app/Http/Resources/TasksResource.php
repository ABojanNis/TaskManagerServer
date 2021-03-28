<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'task' => $this->task,
            'status' => $this->pivot->status,
            'is_expired' => $this->pivot->expired_at < now(),
        ];
    }
}
