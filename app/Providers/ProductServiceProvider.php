<?php

namespace App\Providers;

use App\Repositories\Product\ProductRepository;
use App\Services\Product\Import\ProductLoaderService;
use App\Services\Product\Product\Contracts\ProductStorage;
use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

/**
 * Note: in the future it can be a good idea to add something like --disk option for the import command itself.
 * This would make the command much more flexible, as now disk is defined at config and injected accordingly.
 * (env PRODUCT_IMPORT_DISK with default local).
 *
 */
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
