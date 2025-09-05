<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class QueueUpdated implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $channelName;
    public int $queuePosition;

    /**
     * Create a new event instance.
     */
    public function __construct(string $channelName, int $queuePosition) {
        $this->channelName = $channelName;
        $this->queuePosition = $queuePosition;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            new Channel($this->channelName),
        ];
    }
}
