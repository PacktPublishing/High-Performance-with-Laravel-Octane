<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Octane\Events\RequestReceived;

class RequestReceivedNotification
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
     * @param  \Laravel\Octane\Events\RequestReceived;  $event
     * @return void
     */
    public function handle(RequestReceived $event)
    {
        Log::info('Request Received by '.$event->request->ip());
    }
}
