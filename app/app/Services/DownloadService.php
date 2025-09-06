<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use DomainException;

use App\Models\Download;
use App\Jobs\ConvertVideoToAudio;
use App\Jobs\BroadcastQueueUpdate;
use App\Jobs\DeleteExpiredFile;
use App\Events\LinkProcessed;
use App\Events\QueueUpdated;


class DownloadService {

    public static function processForm(string $link, string $format, int $quality): array {
        Log::debug('processForm - called');
        Log::debug("processForm - link : {$link}");
        Log::debug("processForm - format : {$format}");
        Log::debug("processForm - quality : {$quality}");
        // normalize the link
        // $normalizedLink = self::normalizeLink($link);
        // Log::debug("processForm - normalized link : {$normalizedLink}");
        // check if a download with the same parameters already exists
        // $sameDownload = Download::where('youtube_url', $normalizedLink)
        $sameDownload = Download::where('link', $link)
            ->where('format', $format)
            ->where('quality', $quality)
            ->where('state_id', '<>', 4)
            ->first();
        Log::debug('$sameDownload :');
        Log::debug($sameDownload);
        if ($sameDownload === null) {
            // save the download parameters in the database
            $download = Download::create([
                'link' => $link,
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
            // return the new download
            $jsonResponseData = [
                'id' => $download->id,
                'state' => $download->state_id,
                'fileName' => $download->file_name,
            ];
        }
        else {
            // return the download that was found
            $jsonResponseData = [
                'id' => $sameDownload->id,
                'state' => $sameDownload->state_id,
                'fileName' => $sameDownload->file_name,
            ];
        }
        return $jsonResponseData;
    }

    // protected static function normalizeLink(string $link): string {
    //     // extract the video ID from the submitted link
    //     $queryParts = [];
    //     parse_str(parse_url($link, PHP_URL_QUERY), $queryParts);
    //     $videoID = $queryParts['v'];
    //     // return a normalized link only containing the video ID
    //     return "https://www.youtube.com/watch?v={$videoID}";
    // }

    public static function getStatus(string $downloadID) {
        Log::debug('getStatus - called');
        $download = Download::find($downloadID);
        if ($download === null) {
            throw new DomainException('Download not found.');
        }
        return [
            'queuePosition' => self::getQueuePosition($download),
            'state' => $download->state_id,
            'fileName' => $download->file_name,
            'error' => $download->error,
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
        // make the directory that will contain the file
        Storage::makeDirectory($download->id);
        // launch the yt-dlp process
        // sleep(10);
        // Process::run("touch ./storage/app/public/{$download->id}/prout");
        $ytdlpProcessString = "yt-dlp -x -f bestaudio --audio-format mp3 --audio-quality {$download->quality}"
            ." -o \"/var/www/storage/app/public/{$download->id}/%(title)s.%(ext)s\" --no-cache-dir '{$download->link}'";
        Log::debug('yt-dlp process string :');
        Log::debug($ytdlpProcessString);
        $ytdlpProcessResult = Process::timeout(300)->run($ytdlpProcessString);
        Log::debug('yt-dlp standard output :');
        Log::debug(trim($ytdlpProcessResult->output()));
        Log::debug('yt-dlp error output :');
        Log::debug(trim($ytdlpProcessResult->errorOutput()));
        if ($ytdlpProcessResult->successful()) {
            Log::debug('handleConvertVideoToAudio - processing completed successfully');
            // get the name of the file that was created
            $findFileNameProcessResult = Process::run("ls ./storage/app/public/{$download->id}");
            $fileName = trim($findFileNameProcessResult->output());
            $download->update([
                'state_id' => 3,
                'file_name' => $fileName,
            ]);
            // broadcast the LinkProcessed event
            Log::debug('handleConvertVideoToAudio - broadcasting "LinkProcessed" event');
            LinkProcessed::dispatch($download->id, true, $fileName, null);
            // dispatch the deletion job for the newly downloaded file
            Log::debug('handleConvertVideoToAudio - queueing file deletion job');
            DeleteExpiredFile::dispatch($download)->delay(60*60);
        }
        else {
            Log::debug('handleConvertVideoToAudio - processing failed');
            $error = trim($ytdlpProcessResult->errorOutput());
            $download->update([
                'state_id' => 4,
                'error' => $error,
            ]);
            // broadcast the LinkProcessed event
            Log::debug('handleConvertVideoToAudio - broadcasting "LinkProcessed" event');
            LinkProcessed::dispatch($download->id, false, null, $error);
            // delete the folder that was just created
            Storage::deleteDirectory($download->id);
        }
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

    public static function handleDeleteFile(Download $download): void {
        Log::debug('handleDeleteFile - called');
        Log::debug("handleDeleteFile - deleting \"/storage/{$download->id}/{$download->file_name}\"");
        // delete the file
        Storage::deleteDirectory($download->id);
        // soft delete the download model
        $download->delete();
    }

}
