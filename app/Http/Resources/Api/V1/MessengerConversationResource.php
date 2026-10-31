<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One message in a conversation.
 *
 * `is_mine` is resolved per request rather than stored, so the client does not
 * have to compare user ids against its own session to draw the bubble alignment.
 * `local_code_id` is echoed back so a client that optimistically appended a
 * message can find and reconcile it instead of showing the text twice.
 *
 * @mixin \App\Models\MessengerConversation
 */
class MessengerConversationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'type' => $this->type,
            'local_code_id' => $this->local_code_id,
            'is_mine' => $this->user_id === $request->user()?->id,
            'author' => new AuthorResource($this->whenLoaded('author')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
