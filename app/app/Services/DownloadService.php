<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;
use DomainException;

use App\Models\Download;
use App\Jobs\ConvertVideoToAudio;
use App\Jobs\BroadcastQueueUpdate;
use App\Events\LinkProcessed;
use App\Events\QueueUpdated;


class DownloadService {

    public static function processForm(string $link, string $format, int $quality): Download {
        Log::debug('processForm - called');
        Log::debug("processForm - link : {$link}");
        Log::debug("processForm - format : {$format}");
        Log::debug("processForm - quality : {$quality}");
        // normalize the link
        $normalizedLink = self::normalizeLink($link);
        Log::debug("processForm - normalized link : {$normalizedLink}");
        // check if a download with the same parameters already exists
        // ...
        // save the download parameters in the database
        $download = Download::create([
            'youtube_url' => $normalizedLink,
            'format' => $format,
            'quality' => $quality,
            'state_id' => 1
        ]);
        // start the processing job
        // https://laracasts.com/discuss/channels/laravel/get-job-id-from-dispatch-in-controller
        $jobId = Queue::push(new ConvertVideoToAudio($download), queue: 'downloads');
        Log::debug("processForm - new job ID : {$jobId}");
        // add the job id to the download model
        $download->update([
            'job_id' => $jobId,
        ]);
        return $download;
    }

    protected static function normalizeLink(string $link): string {
        // extract the video ID from the submitted link
        $queryParts = [];
        parse_str(parse_url($link, PHP_URL_QUERY), $queryParts);
        $videoID = $queryParts['v'];
        // return a normalized link only containing the video ID
        return "https://www.youtube.com/watch?v={$videoID}";
    }

    public static function getStatus(string $downloadID) {
        Log::debug('getStatus - called');
        $download = Download::find($downloadID);
        if ($download === null) {
            throw new DomainException('Download not found.');
        }
        return [
            'state' => $download->state_id,
            'fileName' => $download->file_name,
            'queuePosition' => self::getQueuePosition($download),
        ];
    }

    public static function getQueuePosition(Download $download): int {
        Log::debug('getQueuePosition - called');
        if ($download->state_id >= 2) {
            return 0;
        }
        else {
            return DB::table('jobs')
                ->where('queue', 'downloads')
                ->where('id', '<=', $download->job_id)
                ->count() - 1;
        }
    }

    public static function handleConvertVideoToAudio(Download $download): void {
        Log::debug('handleConvertVideoToAudio - called');
        Log::debug("handleConvertVideoToAudio - job ID : {$download->job_id}");
        // update the download state
        $download->update([
            'state_id' => 2,
        ]);
        // broadcast the queue update event to all the waiting clients
        Log::debug('handleConvertVideoToAudio - broadcasting queue update');
        Queue::push(new BroadcastQueueUpdate());
        Log::debug('handleConvertVideoToAudio - processing download');
        // launch the yt-dlp process
        // ...
        sleep(10);
        Log::debug('handleConvertVideoToAudio - processing completed successfully');
        $download->update([
            'state_id' => 3,
            'file_name' => 'blbl.lele',
        ]);
        // broadcast the LinkProcessed event
        Log::debug('handleConvertVideoToAudio - broadcasting "LinkProcessed" event');
        LinkProcessed::dispatch($download->id, true, 'blbl.lele');
    }

    public static function handleBroadcastQueueUpdate(): void {
        Log::debug('handleBroadcastQueueUpdate - called');
        $pendingDownloads = Download::where('state_id', '<=', 2)->get();
        Log::debug('handleBroadcastQueueUpdate - pending downloads :');
        Log::debug($pendingDownloads);
        foreach ($pendingDownloads as $pendingDownload) {
            QueueUpdated::dispatch($pendingDownload->id, self::getQueuePosition($pendingDownload));
        }
    }

}
