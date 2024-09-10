<?php

namespace App\Providers;

use App\Services\Product\Importer\ProductImporterService;
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
            ->when(ProductImporterService::class)
            ->needs(Filesystem::class)
            ->give(function (Container $app)
            {
                return $app->get(FilesystemManager::class)->disk(
                    $app->make('config')->get('products.disk')
                );
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
