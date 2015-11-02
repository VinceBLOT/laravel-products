<?php

namespace Speelpenning\Products;

use Illuminate\Support\ServiceProvider;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository as AttributeRepositoryContract;
use Speelpenning\Contracts\Products\Repositories\AttributeValueRepository as AttributeValueRepositoryContract;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository as ProductTypeRepositoryContract;
use Speelpenning\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Repositories\AttributeValueRepository;
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
        $bindings = [
            AttributeRepositoryContract::class      => AttributeRepository::class,
            AttributeValueRepositoryContract::class => AttributeValueRepository::class,
            ProductTypeRepositoryContract::class    => ProductTypeRepository::class,
        ];

        foreach ($bindings as $contract => $implementation) {
            $this->app->bind($contract, $implementation);
        }
    }

    /**
     * Publishes the migrations.
     */
    protected function publishMigrations()
    {
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');
    }
}
