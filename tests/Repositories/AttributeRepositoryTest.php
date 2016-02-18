<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Attribute;
use Speelpenning\Products\ProductType;

class AttributeRepositoryTest extends TestCase
{
    /**
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var ProductTypeRepository
     */
    protected $productTypeRepository;

    public function setUp()
    {
        parent::setUp();

        $this->attributeRepository = app(AttributeRepository::class);
        $this->productTypeRepository = app(ProductTypeRepository::class);
    }

    public function testItSavesNewAttributes()
    {
        $this->assertEquals(0, $this->attributeRepository->query()->total());
        $this->assertTrue($this->attributeRepository->save(Attribute::instantiate('Length', 'numeric')));
        $this->assertEquals(1, $this->attributeRepository->query()->total());
        $this->seeInDatabase('attributes', ['description' => 'Length', 'type' => 'numeric']);
    }

    public function testItFindsAttributesById()
    {
        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->assertEquals('Length', $this->attributeRepository->find(1)->description);
    }

    public function testItQueriesAttributes()
    {
        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->attributeRepository->save(Attribute::instantiate('Width', 'numeric'));

        $this->assertEquals(1, $this->attributeRepository->query('length')->total());
    }

    public function testItUpdatesExistingProductTypes()
    {
        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->notSeeInDatabase('attributes', ['description' => 'Width']);

        $productType = $this->attributeRepository->find(1)->fill(['description' => 'Width']);
        $this->attributeRepository->save($productType);

        $this->seeInDatabase('attributes', ['description' => 'Width']);
        $this->notSeeInDatabase('attributes', ['description' => 'Length']);
    }

    public function testItDestroysAttributes()
    {
        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->assertEquals(1, $this->attributeRepository->query()->total());

        $this->assertTrue($this->attributeRepository->destroy($this->attributeRepository->find(1)));
        $this->assertEquals(0, $this->attributeRepository->query()->total());
    }

    public function testItReturnsAllAttributes()
    {
        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->attributeRepository->save(Attribute::instantiate('Width', 'numeric'));
        $this->attributeRepository->save(Attribute::instantiate('Height', 'numeric'));

        $this->assertCount(3, $this->attributeRepository->all());
    }

    public function testItRelatesAttributesToProductTypes()
    {
        $productType = ProductType::instantiate('Moving Box');
        $this->productTypeRepository->save($productType);

        $this->attributeRepository->save(Attribute::instantiate('Length', 'numeric'));
        $this->attributeRepository->save(Attribute::instantiate('Width', 'numeric'));
        $this->attributeRepository->save(Attribute::instantiate('Height', 'numeric'));

        $result = $this->attributeRepository->syncWithProductType($productType, [1, 2, 3], [2]);
        $this->assertCount(3, array_get($result, 'attached'));
        $this->assertCount(0, array_get($result, 'detached'));
        $this->assertCount(0, array_get($result, 'updated'));

        $this->seeInDatabase('attribute_product_type', [
            'product_type_id' => $productType->id,
            'attribute_id' => 2,
            'required' => true,
        ]);

        $result = $this->attributeRepository->syncWithProductType($productType, [1, 2], [1]);
        $this->assertCount(0, array_get($result, 'attached'));
        $this->assertCount(1, array_get($result, 'detached'));
        $this->assertCount(2, array_get($result, 'updated'));

        $this->seeInDatabase('attribute_product_type', [
            'product_type_id' => $productType->id,
            'attribute_id' => 1,
            'required' => true,
        ]);

        $this->dontSeeInDatabase('attribute_product_type', [
            'product_type_id' => $productType->id,
            'attribute_id' => 2,
            'required' => true,
        ]);
    }

    public function testItReturnsAttributesRelatedToAProductType()
    {
        $this->testItRelatesAttributesToProductTypes();

        $productType = $this->productTypeRepository->find(1);
        $attributes = $this->attributeRepository->getByProductType($productType);
        $this->assertCount(2, $attributes);
    }
}
