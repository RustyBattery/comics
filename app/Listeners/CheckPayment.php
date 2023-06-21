<?php

namespace App\Listeners;

use App\Events\UserSubscribe;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckPayment implements ShouldQueue
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
     * @param  \App\Events\UserSubscribe  $event
     * @return void
     */
    public function handle(UserSubscribe $event)
    {
        $event->payment->check();
    }
}
