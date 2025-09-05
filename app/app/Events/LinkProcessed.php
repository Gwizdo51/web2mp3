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

    protected string $DownloadID;
    public bool $success;
    public ?string $fileName;

    /**
     * Create a new event instance.
     */
    public function __construct(string $DownloadID, bool $success, ?string $fileName = null) {
        $this->DownloadID = $DownloadID;
        $this->success = $success;
        $this->fileName = $fileName;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array {
        return [
            new Channel($this->DownloadID),
        ];
    }
}
