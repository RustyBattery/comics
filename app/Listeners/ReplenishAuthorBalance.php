<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Events\UserSubscribe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReplenishAuthorBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\PaymentSuccess $event
     * @return void
     */
    public function handle(PaymentSuccess $event)
    {
        $subscription = $event->payment->subscription();
        $author = $subscription->author()->first();
        $author->balance += $subscription->price;
        $author->save();
    }
}
