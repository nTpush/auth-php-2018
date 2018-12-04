<?php

namespace App\Handlers\Events;

use App\Events\PupUserchange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserManagerd
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
     * @param  PupUserchange  $event
     * @return void
     */
    public function handle(PupUserchange $event)
    {
        //
    }
}
