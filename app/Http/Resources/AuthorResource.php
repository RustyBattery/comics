<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;

class AuthorResource extends JsonResource
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
            "user_id" => $this->user_id,
            "nickname" => $this->nickname,
            "about" => $this->about,
            "photo" => $this->photo ? env('APP_URL').'/'.$this->photo : null,
            "books" => $this->books()->whereHas('chapters', function (Builder $query) {
                $query->where('status', 'approved');
            })->count(),
            "followers" => $this->followers()->count(),
            "is_following" => auth()->user() && auth()->user()->favoriteAuthors()->find($this->id) ? true : false,
        ];
    }
}
