<?php

namespace App\Listeners;

use App\Events\PaymentSuccess;
use App\Events\UserSubscribe;
use App\Models\Author;
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
        $amount = $event->payment->get_author_and_amount()['amount'];
        $author = Author::find($event->payment->get_author_and_amount()['author_id']);
        $author->balance += $amount;
        $author->save();
    }
}
