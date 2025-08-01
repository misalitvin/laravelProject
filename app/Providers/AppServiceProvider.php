<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\CurrencyClientInterface;
use App\Interfaces\HttpClientInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Interfaces\Repositories\ManufacturerRepositoryInterface;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Interfaces\Repositories\ServiceRepositoryInterface;
use App\Repositories\CurrencyRateRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceRepository;
use App\Services\EcbCurrencyClient;
use App\Services\LaravelHttpClient;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CurrencyRateRepositoryInterface::class, CurrencyRateRepository::class);
        $this->app->bind(HttpClientInterface::class, LaravelHttpClient::class);
        $this->app->bind(CurrencyClientInterface::class, EcbCurrencyClient::class);

        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ManufacturerRepositoryInterface::class, ManufacturerRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
