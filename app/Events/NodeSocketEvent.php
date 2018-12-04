<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NodeSocketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    protected $message;

    /**
     * NodeSocketEvent constructor.
     * @param $new_message
     */
    public function __construct($new_message)
    {
        //
        $this->message = $new_message;
    }


    public function getMessage() {
        return $this->message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
