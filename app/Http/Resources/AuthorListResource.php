<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Builder;

class AuthorListResource extends JsonResource
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
            "nickname" => $this->nickname,
            "photo" => $this->photo ? env('APP_URL').'/'.$this->photo : null,
            "books" => $this->books()->whereHas('chapters', function (Builder $query) {
                $query->where('status', 'approved');
            })->count(),
            "subscribers" => 0,
            "is_following" => $this->id % 2 == 0 ? true : false,
        ];
    }
}
