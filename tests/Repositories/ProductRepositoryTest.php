<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductNumber;
use Speelpenning\Products\ProductType;

class ProductRepositoryTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @var ProductRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = app(ProductRepository::class);
    }

    /**
     * Creates a product type for testing.
     *
     * @return \Speelpenning\Contracts\Products\ProductType
     */
    public function createProductType()
    {
        $productType = ProductType::instantiate('Dummy');
        app(ProductTypeRepository::class)->save($productType);
        return $productType;
    }

    public function testItSavesNewProducts()
    {
        $productType = $this->createProductType();
        $product = Product::instantiate(ProductNumber::parse('123456'), $productType, 'Testing');

        $this->assertTrue($this->repository->save($product));
    }

    public function testItFindsProductsById()
    {
        $this->testItSavesNewProducts();

        $product = $this->repository->find(1);

        $this->assertEquals('123456', $product->product_number);
        $this->assertEquals('Testing', $product->description);
    }

    public function testItQueriesProductTypes()
    {
        $productType = $this->createProductType();

        $products = [
            Product::instantiate(ProductNumber::parse('123456'), $productType, 'Book'),
            Product::instantiate(ProductNumber::parse('234567'), $productType, 'Mobile phone'),
            Product::instantiate(ProductNumber::parse('345678'), $productType, 'Coffee maker'),
            Product::instantiate(ProductNumber::parse('456789'), $productType, 'Text book'),
        ];

        foreach ($products as $product) {
            $this->repository->save($product);
        }

        $this->assertEquals(2, $this->repository->query('book')->total());
    }
}
