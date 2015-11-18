<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Attribute;
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
    public function createProductTypeAndAttributes()
    {
        $productType = ProductType::instantiate('Dummy');
        app(ProductTypeRepository::class)->save($productType);

        app(AttributeRepository::class)->save(Attribute::instantiate('Length', 'numeric'));
        app(AttributeRepository::class)->save(Attribute::instantiate('Width', 'numeric'));
        app(AttributeRepository::class)->save(Attribute::instantiate('Height', 'numeric'));
        app(AttributeRepository::class)->syncWithProductType([1, 2, 3], $productType);

        return $productType;
    }

    public function testItSavesNewProducts()
    {
        $productType = $this->createProductTypeAndAttributes();
        $product = Product::instantiate(ProductNumber::parse('123456'), $productType, 'Testing');

        $this->assertTrue($this->repository->save($product));
        $this->seeInDatabase('products', $product->toArray());
    }

    public function testItFindsProductsById()
    {
        $this->testItSavesNewProducts();

        $product = $this->repository->find(1);

        $this->assertEquals('123456', $product->product_number);
        $this->assertEquals('Testing', $product->description);
    }

    public function testItQueriesProducts()
    {
        $productType = $this->createProductTypeAndAttributes();

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

    public function testItUpdatesProductsAndTheirAttributes()
    {
        $this->testItSavesNewProducts();

        $product = $this->repository->find(1)->fill(['description' => 'Updated description']);
        $this->repository->save($product, [1 => 5, 2 => 10, 3 => 15]);

        $this->seeInDatabase('products', $product->toArray());
        $this->seeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 1, 'value' => 5]);
        $this->seeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 2, 'value' => 10]);
        $this->seeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 3, 'value' => 15]);

        $this->repository->save($product, [1 => 5, 3 => 10]);
        $this->seeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 1, 'value' => 5]);
        $this->notSeeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 2]);
        $this->seeInDatabase('attribute_product', ['product_id' => $product->id, 'attribute_id' => 3, 'value' => 10]);
    }

    public function testItFetchesTheHighestProductNumber()
    {
        $this->assertEquals('100000', $this->repository->getHighestProductNumber());

        $productType = $this->createProductTypeAndAttributes();
        $this->repository->save(Product::instantiate(
            ProductNumber::parse('123456'),
            $productType,
            'Test product'
        ));

        $this->assertEquals('123456', $this->repository->getHighestProductNumber());
    }
}
