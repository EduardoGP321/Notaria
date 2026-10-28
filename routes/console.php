<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('expedientes:verificar-vencimientos')
    ->dailyAt('08:00')
    ->description('Verifica vencimientos de plazos SLA de expedientes bancarios');
