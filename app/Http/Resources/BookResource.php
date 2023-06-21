<?php

namespace App\Http\Resources;

use App\Models\Author;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'author' => AuthorShortResource::make($this->author),
            'description' => $this->description,
            'photo' => $this->photo ? env('APP_URL').'/'.$this->photo : null,
            'status' => $this->status,
            'rating' => null,
            'genres' => GenreResource::collection($this->genres()->get()),
            'is_free' => $this->price || $this->chapters()->whereNotNull('subscription_id')->where('status', 'approved')->count() ? false : true,
            'price' => $this->price,
            'chapters' => ChapterListResource::collection($this->chapters()->where('status', 'approved')->orderBy('number')->get()),
        ];
    }
}
