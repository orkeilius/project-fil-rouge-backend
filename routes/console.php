<?php

use Carbon\Unit;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();



Schedule::call(function () {
    DB::table('users')->where('created_at', '<', now()->add(Unit::Year,-2))->delete();
})->monthly();

//Schedule::call(function () {
//    DB::table('users')->where('created_at', '<', now()->add(Unit::Day,-2))->delete();
//})->everyFiveSeconds();
