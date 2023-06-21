<?php

namespace App\Http\Resources;

use App\Models\Chapter;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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
            "description" => $this->description,
            'author' => AuthorShortResource::make($this->author()),
            "subscription" => SubscriptionResource::make($min_subscription),
            "count_page" => $this->pages()->count(),
            'rating' => null,
            "is_available" => $is_available,
            "pages" => $is_available ? PageResource::collection($this->pages()->get()) : null,
            "prev_chapter_id" => Chapter::where('status', 'approved')->where('number', $this->number - 1)->first()->id ?? null,
            "next_chapter_id" => Chapter::where('status', 'approved')->where('number', $this->number + 1)->first()->id ?? null,
        ];
    }
}
