<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Speelpenning\Contracts\Products\ProductType;
use Speelpenning\Contracts\Products\Repositories\AttributeRepository;
use Speelpenning\Products\Attribute;
use Speelpenning\Products\Events\ProductTypeWasDestroyed;
use Speelpenning\Products\Events\ProductTypeWasStored;
use Speelpenning\Products\Events\ProductTypeWasUpdated;
use Speelpenning\Products\Jobs\DestroyProductType;
use Speelpenning\Products\Jobs\StoreProductType;
use Speelpenning\Products\Jobs\UpdateProductType;

class ProductTypeJobsTest extends TestCase
{
    use DispatchesJobs;

    protected function storeProductType($description)
    {
        return $this->dispatchFromArray(StoreProductType::class, compact('description'));
    }

    protected function createAttributes()
    {
        $repository = app(AttributeRepository::class);

        $repository->save(Attribute::instantiate('Attribute 1', 'string'));
        $repository->save(Attribute::instantiate('Attribute 2', 'numeric'));
        $repository->save(Attribute::instantiate('Attribute 3', 'in'));

        return $repository->all();
    }

    public function testStoreProductType()
    {
        $description = 'Personal Computer';
        $this->expectsEvents(ProductTypeWasStored::class);

        $productType = $this->storeProductType($description);

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertNotNull($productType->id);
        $this->assertEquals($description, $productType->description);

        $this->seeInDatabase('product_types', compact('description'));
    }

    public function testUpdateProductType()
    {
        $id = $this->storeProductType('Description to be updated')->id;
        $description = 'Updated description';

        $this->expectsEvents(ProductTypeWasUpdated::class);

        $productType = $this->dispatchFromArray(UpdateProductType::class, compact('id', 'description'));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertEquals($description, $productType->description);

        $this->seeInDatabase('product_types', compact('description'));
    }

    public function testDestroyProductType()
    {
        $id = $this->storeProductType('Product type to be destroyed')->id;
        $this->expectsEvents(ProductTypeWasDestroyed::class);

        $productType = $this->dispatchFromArray(DestroyProductType::class, compact('id'));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertNotNull($productType->deleted_at);
    }

    public function testAttributesCanBeAssociatedAndDissociated()
    {
        $attributes = $this->createAttributes()->lists('id')->toArray();

        $productType = $this->storeProductType('Some product type');

        $this->dispatchFromArray(UpdateProductType::class, [
            'id' => $productType->id,
            'description' => $productType->description,
            'attributes' => $attributes,
        ]);

        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 1]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 2]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 3]);

        $this->dispatchFromArray(UpdateProductType::class, [
            'id' => $productType->id,
            'description' => $productType->description,
            'attributes' => array_only($attributes, [1]),
        ]);

        $this->notSeeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 1]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 2]);
        $this->notSeeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 3]);
    }
}
