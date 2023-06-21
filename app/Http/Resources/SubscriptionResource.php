<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $is_active = false;
        $date_end = null;

        if(auth()->user()){
            $is_active = auth()->user()->subscriptions()->where('subscriptions.id', $this->id)->whereDate('user_subscriptions.date_end', '>', Carbon::now())->count() ? true : false;
            $subscription = auth()->user()->subscriptions()->where('subscriptions.id', $this->id)->whereDate('user_subscriptions.date_end', '>', Carbon::now())->first();
            $date_end = $subscription->pivot->date_end ?? null;
        }
        return [
            'id' => $this->id,
            'author_id' => $this->author_id,
            'author' => AuthorShortResource::make($this->author),
            'name' => $this->name,
            'description' => $this->description,
            'photo' => $this->photo ? env('APP_URL').'/'.$this->photo : null,
            'price' => $this->price,
            'is_active' => $is_active,
            'date_end' => $date_end,
        ];
    }
}
