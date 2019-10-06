<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendGroupResource extends JsonResource
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
            'friend_group_name' => $this->name,
            'owner_user' => new UserResource(User::find($this->user_id)),
            'friends' => FriendResource::collection($this->friends)
        ];
    }
}
