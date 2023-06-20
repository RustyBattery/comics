<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
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
            "description" => $this->description,
            'author' => AuthorShortResource::make($this->author()),
            "subscription" => SubscriptionResource::make($this->subscription()->first()),
            "count_page" => $this->pages()->count(),
            'rating' => null,
            "is_available" => $this->number > 2 ? false : true,
            "pages" => $this->number > 2 ? null : PageResource::collection($this->pages()->get()),
        ];
    }
}
