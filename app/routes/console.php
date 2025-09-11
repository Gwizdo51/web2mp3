<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Process;

use App\Events\LinkProcessed;


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
// })->purpose('Display a new set of credentials');;
