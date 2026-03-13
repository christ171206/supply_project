<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendDeliveryReminders;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule jobs pour les rappels et alertes
 */

// Vérification des alertes de stock - chaque heure
Schedule::command('alerts:check-stock')
    ->hourly()
    ->name('check-stock-alerts')
    ->onOneServer()
    ->withoutOverlapping();

// Envoi des rappels de livraison - 2 fois par jour
Schedule::job(new SendDeliveryReminders())
    ->twiceDaily(8, 20) // 8:00 et 20:00 chaque jour
    ->name('send-delivery-reminders')
    ->onOneServer()
    ->withoutOverlapping();
