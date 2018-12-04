<?php

namespace App\Listeners;

use App\Events\NodeSocketEvent;
use GuzzleHttp\Client;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;

class NodeSocketListener
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

    public function getM() {
        return 3;
    }

    /**
     * Handle the event.
     *
     * @param  NodeSocketEvent  $event
     * @return void
     */
    public function handle(NodeSocketEvent $event)
    {
        //

//        $message = $event->getMessage();
//
//        $client = new Client();
//
//        $res = $client->request('GET', 'http://127.0.0.1:3001/login', [
//            "message" => $message
//        ]);
//
//        $data = $res->getBody();
        $data = [
            'name' => 'shining'
        ];
        Redis::publish('test-channel', json_encode($data));

//        return $data;
    }
}
