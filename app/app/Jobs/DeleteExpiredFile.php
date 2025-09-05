<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\Download;
use App\Services\DownloadService;


class DeleteExpiredFile implements ShouldQueue {
    use Queueable;

    protected Download $download;

    /**
     * Create a new job instance.
     */
    public function __construct(Download $download) {
        $this->download = $download;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        DownloadService::handleDeleteFile($this->download);
    }
}
