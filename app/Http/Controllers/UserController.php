<?php

namespace App\Http\Controllers;

use App\Events\UserSubscribe;
use App\Http\Requests\UserSubscriptionCreateRequest;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //создаю сущность
    //прикрепляю к ней платеж
    //инициирую платеж

    public function subscribe(Subscription $subscription, UserSubscriptionCreateRequest $request){
        $data = $request->validated();
        $user = auth()->user();
        $user->subscriptions()->attach($subscription);
        $payment = Payment::create();
        $user->subscriptions()->updateExistingPivot($subscription->id, ["payment_id" => $payment->id, "date_end" => Carbon::now()->addMonth()]);
        $payment_link = $payment->init($subscription->price, $data['success_url'], UserSubscribe::class);
        return response(["payment_link" => $payment_link], 200);
    }
}
