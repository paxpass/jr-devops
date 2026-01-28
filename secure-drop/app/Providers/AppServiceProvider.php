<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\SecretRepository;
use App\Repositories\SecretRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            SecretRepositoryInterface::class,
            SecretRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}