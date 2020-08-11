<?php

namespace App\Providers;

use App\Services\Wikipedia\WikipediaServiceInteface;
use App\Services\Wikipedia\WikipediaService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiter;


class WikipediaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(WikipediaServiceInteface::class, function () {
            return new WikipediaService('en', new Client(), app(RateLimiter::class));
        });
    }
}
