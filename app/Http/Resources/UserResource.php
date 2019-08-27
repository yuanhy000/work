<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_id' => $this->id,
            'user_name' => $this->name,
            'user_number' => $this->number,
            'user_email' => $this->email,
            'user_phone' => $this->phone,
            'user_avatar' => $this->avatar,
            'user_sex' => $this->sex,
            'user_created' => $this->created_at
        ];
    }
}
