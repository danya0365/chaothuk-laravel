<?php

namespace App\Http\Resources;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserNotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'list' => $this->collection->map(function ($notification) {

                $notificationable =  $notification->notificationable;
                if ($notificationable instanceof Work) {
                    $notificationable = [
                        'id' => $notificationable->id,
                        'title' => $notificationable->title,
                        'primary_image' => $notificationable->primary_image,
                    ];
                }

                $commentData = [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'created_at' => $notification->created_at,
                    'notification_type' => $notification->notification_type,
                    'notificationable' => $notificationable,
                ];

                return $commentData;
            })
        ];
    }
}
