<?php

namespace App\Listeners;

use App\Events\AccessTokenCreated;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;

class RevokeOldTokens
{
    /**
     * RevokeOldTokens constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        //

        Token::
           where('expires_at', '<', Carbon::now())
            ->orWhere('revoked', true)
            ->delete();


        DB::table('oauth_refresh_tokens')
            ->where('expires_at', '<', Carbon::now())
            ->orWhere('revoked', true)->delete();
    }
}
