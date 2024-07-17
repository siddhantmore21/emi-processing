<?php

namespace App\Providers;

use App\Models\EmiDetails;
use App\Models\LoanDetails;
use App\Repositories\EmiDetailsRepository;
use App\Repositories\LoanDetailsRepository;
use App\Services\EmiDetailsService;
use App\Services\LoanDetailsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LoanDetailsRepository::class, function ($app) {
            return new LoanDetailsRepository(new LoanDetails());
        });

        $this->app->bind(LoanDetailsService::class, function ($app) {
            return new LoanDetailsService($app->make(LoanDetailsRepository::class));
        });

        $this->app->bind(EmiDetailsRepository::class, function ($app) {
            return new EmiDetailsRepository(new EmiDetails());
        });

        $this->app->bind(EmiDetailsService::class, function ($app) {
            return new EmiDetailsService($app->make(EmiDetailsRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
