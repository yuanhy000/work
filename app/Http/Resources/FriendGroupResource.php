<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
            'friends' => FriendResource::collection($this->friends),
            'total_count' => $this->friends->count(),
            'online_count' => $this->when(true, function () {
                $online_count = 0;
                foreach ($this->friends as $friend) {
                    if ($friend->user->status == 1) {
                        $online_count++;
                    }
                }
                return $online_count;
            }),

        ];
    }
}
