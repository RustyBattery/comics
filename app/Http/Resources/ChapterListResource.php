<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "number" => $this->number,
            "name" => $this->name,
            'rating' => null,
            "subscription" => SubscriptionResource::make($this->subscription()->first()),
            "is_available" => $this->number > 2 ? false : true,
        ];
    }
}
