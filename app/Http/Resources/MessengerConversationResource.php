<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessengerConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'type' => $this->type,
            'content' => $this->content,
            'local_code_id' => $this->local_code_id,
            'delivered_at' => $this->delivered_at,
            'seen_at' => $this->seen_at,
            'user_id' => $this->user_id,
            'channel_id' => $this->channel_id,
            'author' => $this->author,
            'channel' => $this->channel,
            'created_at' => $this->created_at
        ];
    }
}
