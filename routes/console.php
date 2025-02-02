<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    DB::table("jobs")->truncate();
    DB::table("job_batches")->truncate();
    DB::table("failed_jobs")->truncate();
})->description("Clear all jobs")->daily();

Schedule::command('auth:clear-resets')->everyFifteenMinutes();
