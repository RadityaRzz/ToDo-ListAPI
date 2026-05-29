<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'title'        => $this->title,
            'description'  => $this->description,
            'category'     => $this->category,
            'sub_category' => $this->sub_category,
            'status'       => $this->status,
            'due_date'     => $this->due_date?->format('Y-m-d'),
            'is_public'    => $this->is_public,
            'created_at'   => $this->created_at->toDateTimeString(),
            'updated_at'   => $this->updated_at->toDateTimeString(),
        ];
    }
}
