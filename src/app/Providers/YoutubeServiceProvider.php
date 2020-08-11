<?php

namespace App\Providers;

use App\Services\Youtube\YoutubeServiceInteface;
use App\Services\Youtube\YoutubeService;
use GuzzleHttp\Client;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\ServiceProvider;

/**
 * Register youtubes service interact with youtube api
 */
class YoutubeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(YoutubeServiceInteface::class, function () {
            return new YoutubeService(env('YOUTUBE_API_KEY'), [], new Client(), app(RateLimiter::class));
        });
    }
}
