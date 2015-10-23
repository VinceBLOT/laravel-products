<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Attribute;

class AttributeRepositoryTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @var AttributeRepository
     */
    protected $repository;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish');
        $this->artisan('migrate:refresh');

        $this->repository = app(AttributeRepository::class);
    }

    public function testItSavesNewAttributes()
    {
        $this->assertEquals(0, $this->repository->query()->total());
        $this->assertTrue($this->repository->save(Attribute::instantiate('Length', 'numeric')));
        $this->assertEquals(1, $this->repository->query()->total());
        $this->seeInDatabase('attributes', ['description' => 'Length', 'type' => 'numeric']);
    }

    public function testItFindsAttributesById()
    {
        $this->repository->save(Attribute::instantiate('Length', 'numeric'));
        $this->assertEquals('Length', $this->repository->find(1)->description);
    }

    public function testItQueriesAttributes()
    {
        $this->repository->save(Attribute::instantiate('Length', 'numeric'));
        $this->repository->save(Attribute::instantiate('Width', 'numeric'));

        $this->assertEquals(1, $this->repository->query('length')->total());
    }

    public function testItUpdatesExistingProductTypes()
    {
        $this->repository->save(Attribute::instantiate('Length', 'numeric'));
        $this->notSeeInDatabase('attributes', ['description' => 'Width']);

        $productType = $this->repository->find(1)->fill(['description' => 'Width']);
        $this->repository->save($productType);

        $this->seeInDatabase('attributes', ['description' => 'Width']);
        $this->notSeeInDatabase('attributes', ['description' => 'Length']);
    }

    public function testItDestroysProductTypes()
    {
        $this->repository->save(Attribute::instantiate('Length', 'numeric'));
        $this->assertEquals(1, $this->repository->query()->total());

        $this->assertTrue($this->repository->destroy($this->repository->find(1)));
        $this->assertEquals(0, $this->repository->query()->total());
    }

    public function testItReturnsAllAttributes()
    {
        $this->repository->save(Attribute::instantiate('Length', 'numeric'));
        $this->repository->save(Attribute::instantiate('Width', 'numeric'));
        $this->repository->save(Attribute::instantiate('Height', 'numeric'));

        $this->assertCount(3, $this->repository->all());
    }
}