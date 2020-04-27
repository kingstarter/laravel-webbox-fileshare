<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;

/**
 * Simple cron test command to display current time
 */
Artisan::command('cron:test', function () {
  $this->info((new Carbon())->toDateTimeLocalString());
});
