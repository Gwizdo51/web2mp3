<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Queue;

use App\Models\Download;
// use App\Events\LinkProcessed;
use App\Services\DownloadService;


class ConvertVideoToAudio implements ShouldQueue {
    use Queueable;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0; // infinite tries
    protected Download $download;

    /**
     * Create a new job instance.
     */
    public function __construct(Download $download) {
        $this->download = $download;
        // $this->onQueue('downloads');
    }

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array {
        // prevent 2 videos from being processed at the same time
        return [(new WithoutOverlapping('download'))->releaseAfter(1)];
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        // Log::debug('ConvertVideoToAudio - handler called');
        // Log::debug("ConvertVideoToAudio - download ID : {$this->download->id}");
        // broadcast the queue update
        // Queue::push(new BroadcastQueueUpdate());
        // launch the process
        // sleep(10);
        DownloadService::handleConvertVideoToAudio($this->download);
        // Log::debug("ConvertVideoToAudio - download finished (ID : {$this->download->id})");
        // LinkProcessed::dispatch($this->download->id, true);
    }
}
