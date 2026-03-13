<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderStatusChanged;
use App\Events\StockAlertTriggered;
use App\Listeners\SendVendorOrderNotification;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SendStockAlertNotification;
use App\Listeners\SendOrderStatusNotification;
use App\Listeners\CreateStockAlertNotification;
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
        OrderStatusChanged::class => [
            SendOrderStatusNotification::class,
        ],
        StockAlertTriggered::class => [
            SendStockAlertNotification::class,
            CreateStockAlertNotification::class,
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
