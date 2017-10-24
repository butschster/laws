<?php

namespace App\Providers;

use App\Services\Dadata\Client as DadataClient;
use App\Services\Dadata\ClientInterface;
use Carbon\Carbon;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClientInterface::class, function ($app) {
            $httpClient = $app->make(HttpClient::class);

            return new DadataClient($httpClient, $app['config']->get('services.dadata.token'));
        });
    }
}
