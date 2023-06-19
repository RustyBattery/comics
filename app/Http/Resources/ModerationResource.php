<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModerationResource extends JsonResource
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

            'book_id' => $this->book->id,
            'book_name' => $this->book->name,
            'chapter_id' => $this->id,
            'chapter_name' => $this->name,
            'chapter_number' => $this->number,
            'status' => $this->status,
            'sending_time' => $this->created_at,
            'update_time' => $this->updated_at,
            'reason' => $this->reason,
        ];
    }
}
