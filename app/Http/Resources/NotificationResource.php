<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'notification_id' => $this->id,
            'notification_content' => $this->content,
            'notification_status' => $this->status,
            'notification_operation' => $this->operation,
            'notification_type' => $this->type,
            'request_user' => new UserResource(User::find($this->from_user_id)),
            'request_time' => date("Y-m-d H:i:s",strtotime($this->created_at)),
        ];
    }
}
