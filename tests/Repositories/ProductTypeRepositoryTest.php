<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;

class ProductTypeRepositoryTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @var ProductTypeRepository
     */
    protected $repository;

    /**
     * Array holding some example product type descriptions.
     *
     * @var array
     */
    protected $descriptions = ['Book', 'Coffee maker', 'Computer', 'TV'];

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish');
        $this->artisan('migrate:refresh');

        $this->repository = app(ProductTypeRepository::class);
    }

    protected function saveMany()
    {
        foreach ($this->descriptions as $description) {
            $this->repository->save(ProductType::instantiate($description));
        }
    }

    public function testItSavesNewProductTypes()
    {
        $this->assertEquals(0, $this->repository->query()->total());
        $this->assertTrue($this->repository->save(ProductType::instantiate('Book')));
        $this->assertEquals(1, $this->repository->query()->total());
        $this->seeInDatabase('product_types', ['description' => 'Book']);
    }

    public function testItFindsProductTypesById()
    {
        $this->saveMany();
        $this->assertEquals('Coffee maker', $this->repository->find(2)->description);
    }

    public function testItQueriesProductTypes()
    {
        $this->saveMany();

        $this->assertEquals(1, $this->repository->query('computer')->total());
    }

    public function testItUpdatesExistingProductTypes()
    {
        $this->saveMany();
        $this->notSeeInDatabase('product_types', ['description' => 'Table']);

        $productType = $this->repository->find(2)->fill(['description' => 'Table']);
        $this->repository->save($productType);

        $this->seeInDatabase('product_types', ['description' => 'Table']);
        $this->notSeeInDatabase('product_types', ['description' => 'Coffee maker']);
    }

    public function testItDestroysProductTypes()
    {
        $this->saveMany();
        $this->assertEquals(count($this->descriptions), $this->repository->query()->total());

        $this->assertTrue($this->repository->destroy($this->repository->find(2)));
        $this->assertEquals(count($this->descriptions) - 1, $this->repository->query()->total());
    }
}
