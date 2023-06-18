<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorBooksResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->photo ? env('APP_URL').'/'.$this->photo : null,
            'status' => $this->status,
            'approved_chapters' => $this->chapters()->where('status', 'approved')->count(),
            'pending_chapters' => $this->chapters()->where('status', 'pending')->count(),
            'declined_chapters' => $this->chapters()->where('status', 'declined')->count(),
            'rating' => null,
            'genres' => GenreResource::collection($this->genres()->get()),
            'is_free' => $this->price || $this->chapters()->whereNotNull('subscription_id')->count() ? false : true,
            'price' => $this->price,
        ];
    }
}
