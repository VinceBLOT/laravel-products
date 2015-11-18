<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\Repositories\ProductRepository;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductNumber;
use Speelpenning\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;

class ProductTypeRepositoryTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @var ProductTypeRepository
     */
    protected $productTypeRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * Array holding some example product type descriptions.
     *
     * @var array
     */
    protected $descriptions = ['Book', 'Coffee maker', 'Computer', 'TV'];

    public function setUp()
    {
        parent::setUp();

        $this->productTypeRepository = app(ProductTypeRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    protected function saveMany()
    {
        foreach ($this->descriptions as $description) {
            $this->productTypeRepository->save(ProductType::instantiate($description));
        }
    }

    public function testItSavesNewProductTypes()
    {
        $this->assertEquals(0, $this->productTypeRepository->query()->total());
        $this->assertTrue($this->productTypeRepository->save(ProductType::instantiate('Book')));
        $this->assertEquals(1, $this->productTypeRepository->query()->total());
        $this->seeInDatabase('product_types', ['description' => 'Book']);
    }

    public function testItFindsProductTypesById()
    {
        $this->saveMany();
        $this->assertEquals('Coffee maker', $this->productTypeRepository->find(2)->description);
    }

    public function testItQueriesProductTypes()
    {
        $this->saveMany();

        $this->assertEquals(1, $this->productTypeRepository->query('computer')->total());
    }

    public function testItUpdatesExistingProductTypes()
    {
        $this->saveMany();
        $this->notSeeInDatabase('product_types', ['description' => 'Table']);

        $productType = $this->productTypeRepository->find(2)->fill(['description' => 'Table']);
        $this->productTypeRepository->save($productType);

        $this->seeInDatabase('product_types', ['description' => 'Table']);
        $this->notSeeInDatabase('product_types', ['description' => 'Coffee maker']);
    }

    public function testItDestroysProductTypes()
    {
        $this->saveMany();
        $this->assertEquals(count($this->descriptions), $this->productTypeRepository->query()->total());

        $this->assertTrue($this->productTypeRepository->destroy($this->productTypeRepository->find(2)));
        $this->assertEquals(count($this->descriptions) - 1, $this->productTypeRepository->query()->total());
    }

    public function testItFindsProductTypesByProduct()
    {
        $this->saveMany();
        $productType = $this->productTypeRepository->find(2);

        $product = Product::instantiate(
            ProductNumber::parse('123456'),
            $productType,
            'Coffee maker'
        );
        $this->productRepository->save($product);

        $type = $this->productTypeRepository->getByProduct($product);
        $this->assertInstanceOf(ProductType::class, $type);
        $this->assertEquals(2, $productType->id);
    }

    public function testItFetchesAllProductTypes()
    {
        $this->saveMany();

        $this->assertCount(count($this->descriptions), $this->productTypeRepository->all());
    }
}
