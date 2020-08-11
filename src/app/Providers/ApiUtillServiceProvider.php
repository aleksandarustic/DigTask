<?php

namespace App\Providers;

use App\Utill\Api\CountryApiUtill;
use App\Utill\CountryUtillInterface;
use Illuminate\Support\ServiceProvider;


/**
 * Register ApiUtill service 
 */
class ApiUtillServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CountryUtillInterface::class, CountryApiUtill::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
