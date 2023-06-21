<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthorStatisticsResource extends JsonResource
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
            "balance" => $this->balance,
            "withdraw_money" => $this->withdraw_money,
            "total_earned" => $this->balance + $this->withdraw_money,
            "subscribers" => $this->subscribers()->count(),
            "followers" => $this->followers()->count(),
        ];
    }
}
