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
        $this->extendUserModel();

        if(file_exists($file = __DIR__.'/Helpers/helpers.php')){
            require $file;
        }

        // Register command
        //if($this->app->runningInConsole()){
        //    $this->command([
        //        DoSomething::class
        //    ]);
        //}
    }

    protected function extendUserModel()
    {
        // Retrieve the User model class from the configuration
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);

        // Use model macros to add relationships dynamically
        $userModelClass::macro('bookings', function () {
            return $this->hasMany(\Davidle90\Bookings\app\Models\Booking::class, 'user_id');
        });
    }
}
