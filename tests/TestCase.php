<?php

use Speelpenning\Products\ProductsServiceProvider;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish', ['--force' => true]);
        $this->artisan('migrate');
    }

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->configureLaravel();

        $app->register(ProductsServiceProvider::class);

        return $app;
    }

    /**
     * Configures Laravel for testing.
     */
    protected function configureLaravel()
    {
        config([
            'database.default' => 'sqlite',
//            'database.connections.sqlite.database' => ':memory:',
        ]);
    }
}