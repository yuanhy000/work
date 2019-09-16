<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ZodiacResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'zodiac_id' => $this->id,
            'zodiac_name' => $this->name,
            'zodiac_icon' => $this->icon
        ];
    }
}
