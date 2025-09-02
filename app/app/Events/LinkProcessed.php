<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkProcessed implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $channelName;
    protected bool $success;

    /**
     * Create a new event instance.
     */
    public function __construct(string $channelName, bool $success) {
        $this->channelName = $channelName;
        $this->success = $success;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            // new Channel('test-channel'),
            new Channel($this->channelName),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array {
        return [
            'success' => $this->success,
        ];
    }
}
