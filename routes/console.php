<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('bignews', function () {
    broadcast(new \App\Events\News(date('Y-m-d h:i:s A').": BIG NEWS!"));
    $this->comment("news sent");
})->describe('Send news');

Artisan::command('sendMessage', function () {
    $data = [
      'name' => 'shining'
    ];
    broadcast(\Illuminate\Support\Facades\Redis::publish('test-channel', json_encode($data)));
    $this->comment("news sent");
})->describe('Send news');