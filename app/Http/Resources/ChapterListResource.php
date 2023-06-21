<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
        $min_subscription = $this->subscription()->first();
        $subscriptions = $min_subscription ? $this->author()->subscriptions()->where('price', '>=', $min_subscription->price)->pluck('id') : [];
        $is_available = (auth()->user() && auth()->user()->subscriptions()->whereIn('subscriptions.id', $subscriptions)->whereDate('user_subscriptions.date_end', '>', Carbon::now())->first()) || !$min_subscription ? true : false;

        return [
            "id" => $this->id,
            "number" => $this->number,
            "name" => $this->name,
            'rating' => null,
            "subscription" => SubscriptionResource::make($min_subscription),
            "is_available" => $is_available,
        ];
    }
}
