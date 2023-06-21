<?php

namespace App\Http\Controllers;

use App\Events\UserSubscribe;
use App\Http\Requests\UserSubscriptionCreateRequest;
use App\Http\Resources\AuthorListResource;
use App\Models\Author;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function subscribe(Subscription $subscription, UserSubscriptionCreateRequest $request){
        $data = $request->validated();
        $user = auth()->user();
        $user->subscriptions()->attach($subscription);
        $payment = Payment::create();
        $user->subscriptions()->updateExistingPivot($subscription->id, ["payment_id" => $payment->id, "date_end" => Carbon::now()->addMonth()]);
        $payment_link = $payment->init($subscription->price, $data['success_url'], UserSubscribe::class);
        return response(["payment_link" => $payment_link], 200);
    }

    public function follow_author(Author $author){
        $user = auth()->user();
        if($user->favoriteAuthors()->find($author->id)){
            $user->favoriteAuthors()->detach($author);
        }
        else{
            $user->favoriteAuthors()->attach($author);
        }
        return response([], 200);
    }

    public function get_favorite_authors(){
        $user = auth()->user();
        return response([AuthorListResource::collection($user->favoriteAuthors()->get())], 200);
    }
}
