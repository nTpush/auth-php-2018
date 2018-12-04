<?php

namespace App\Handlers\Events;

use App\Events\RevokeOldTokens;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RevokeOldTokens
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
     * @param  RevokeOldTokens  $event
     * @return void
     */
    public function handle(RevokeOldTokens $event)
    {
        //
    }
}
