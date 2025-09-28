<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

use App\Events\LinkProcessed;


// COMMANDS

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Artisan::command('reverb:test', function () {
//     LinkProcessed::dispatch('channel-name', true);
//     $this->comment('Event "LinkProcessed" dispatched');
// })->purpose('Test reverb event broadcast');

// Artisan::command('printURL', function () {
//     $this->comment(Storage::url('V.I.C.A.R.I. - Pascià - Original Mix (Robsoul).mp3'));
// });

// Artisan::command('listFiles', function () {
//     $result = Process::run('ls ./storage/app/public');
//     $this->comment($result->output());
// });

// Artisan::command('mkdir', function () {
//     Storage::makeDirectory('prout');
// });

// Artisan::command('rmDir', function () {
//     $result = Process::run('rm -r ./storage/app/public/dir');
//     $this->comment($result->output());
// });

// Artisan::command('reverb:generate', function () {
//     ['id' => $id, 'key' => $key, 'secret' => $secret] = generate_reverb_credentials();
//     $this->info('ID :');
//     $this->warn("    {$id}");
//     $this->info('KEY :');
//     $this->warn("    {$key}");
//     $this->info('SECRET :');
//     $this->warn("    {$secret}");
// })->purpose('Display a new set of credentials');

Artisan::command('update-yt-dlp', function () {
    Log::debug('update-yt-dlp - updating yt-dlp');
    $this->line('update-yt-dlp - updating <options=bold>yt-dlp</>');
    $result = Process::run('yt-dlp -U');
    Log::debug('update-yt-dlp - standard output :');
    $this->line('update-yt-dlp - standard output :');
    Log::debug(trim($result->output()));
    $this->info(trim($result->output()));
    Log::debug('update-yt-dlp - error output :');
    $this->line('update-yt-dlp - error output :');
    Log::error(trim($result->errorOutput()));
    $this->error(trim($result->errorOutput()));
})->purpose('Update the yt-dlp binary');

// SCHEDULED TASKS

Schedule::command('update-yt-dlp')
    ->dailyAt('04:00'); // UTC time
    // ->daily();

// Schedule::call(function () {
//     Log::debug("called with schedule:work");
// })->everyMinute();
