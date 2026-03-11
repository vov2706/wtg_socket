<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Users\UserResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Message */
class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'read_at' => $this->read_at,
            'receiver' => UserResource::make($this->whenLoaded('receiver')),
            'sender' => UserResource::make($this->whenLoaded('sender')),
        ];
    }
}
