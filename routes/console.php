<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Page;
use App\Jobs\CheckPageUptime;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Page::query()->each(function (Page $page) {
        CheckPageUptime::dispatch($page);
    });
})->everyThirtySeconds();
