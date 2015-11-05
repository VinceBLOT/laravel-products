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
}
