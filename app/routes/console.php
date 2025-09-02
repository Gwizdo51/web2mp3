<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Events\LinkProcessed;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reverb:test', function () {
    LinkProcessed::dispatch('channel-name', true);
    $this->comment('Event "LinkProcessed" dispatched');
})->purpose('Test reverb event broadcast');
