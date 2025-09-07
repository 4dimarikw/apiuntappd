<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Services\Untappd\UntappdService;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UntappdService::class, function ($app) {
            return new UntappdService();
        });
    }

}
