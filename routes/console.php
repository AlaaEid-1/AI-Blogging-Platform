<?php

use App\Jobs\SendNewPostsSummary;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(new SendNewPostsSummary())->weeklyOn('mon', '15:40');

Schedule::command('model:prune')->daily();
