<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    if (Storage::exists('temp/pdfs')) {
        $files = Storage::files('temp/pdfs');
        $now = time();
        foreach ($files as $file) {
            if ($now - Storage::lastModified($file) > 3600) {
                Storage::delete($file);
            }
        }
    }
})->hourly()->name('cleanup-temp-pdfs');
