<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\StockAlertTriggered;
use App\Listeners\SendVendorOrderNotification;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SendStockAlertNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendWelcomeEmail::class,
        ],
        OrderCreated::class => [
            SendVendorOrderNotification::class,
        ],
        StockAlertTriggered::class => [
            SendStockAlertNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
