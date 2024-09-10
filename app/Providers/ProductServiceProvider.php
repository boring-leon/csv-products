<?php

namespace App\Providers;

use App\Repositories\Product\ProductRepository;
use App\Services\Product\Import\ProductLoaderService;
use App\Services\Product\Product\Contracts\ProductStorage;
use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app
            ->when(ProductLoaderService::class)
            ->needs(Filesystem::class)
            ->give(function (Container $app)
            {
                return $app->get(FilesystemManager::class)->disk(
                    $app->make('config')->get('products.disk')
                );
            });

        $this->app->bind(ProductStorage::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
