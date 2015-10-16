<?php

namespace Speelpenning\Products;

use Illuminate\Support\ServiceProvider;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository as ProductTypeRepositoryContract;
use Speelpenning\Products\Repositories\ProductTypeRepository;

class ProductsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'products');
        $this->publishMigrations();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->bindRepositories();
    }

    /**
     * Binds the repository abstracts to the actual implementations.
     */
    protected function bindRepositories()
    {
        $this->app->bind(ProductTypeRepositoryContract::class, function () {
            return new ProductTypeRepository();
        });
    }

    /**
     * Publishes the migrations.
     */
    protected function publishMigrations()
    {
        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'migrations');
    }
}
