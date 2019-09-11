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
            'user_age' => $this->age,
            'user_created' => $this->created_at,
            'user_signature' => $this->signature,
            'user_birth' => $this->birth,
            'user_blood_type' => $this->blood_type,
            'user_address' => $this->address,
            'user_hometown' => $this->hometown,
            'user_school' => $this->school,
            'user_constellation' => new ConstellationResource($this->constellation),
            'user_zodiac' => new ZodiacResource($this->zodiac)
        ];
    }
}
