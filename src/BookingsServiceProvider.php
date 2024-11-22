<?php

namespace davidle90\bookings;

use Illuminate\Support\ServiceProvider;

class BookingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/bookings.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'bookings');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->mergeConfigFrom(__DIR__.'/config/bookings.php', 'bookings');

        // Register command
        //if($this->app->runningInConsole()){
        //    $this->command([
        //        DoSomething::class
        //    ]);
        //}
    }
}
