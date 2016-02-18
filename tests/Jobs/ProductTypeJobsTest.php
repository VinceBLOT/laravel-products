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
        return $this->dispatch(new StoreProductType($description));
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

        $productType = $this->dispatch(new UpdateProductType($id, $description));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertEquals($description, $productType->description);

        $this->seeInDatabase('product_types', compact('description'));
    }

    public function testDestroyProductType()
    {
        $id = $this->storeProductType('Product type to be destroyed')->id;
        $this->expectsEvents(ProductTypeWasDestroyed::class);

        $productType = $this->dispatch(new DestroyProductType($id));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertNotNull($productType->deleted_at);
    }

    public function testAttributesCanBeAssociatedAndDissociated()
    {
        $productType = $this->storeProductType('Some product type');
        $attributes = $this->createAttributes()->pluck('id')->toArray();

        $this->dispatch(new UpdateProductType($productType->id, $productType->description, $attributes));

        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 1]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 2]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 3]);

        $this->dispatch(new UpdateProductType($productType->id, $productType->description, array_only($attributes, [1])));

        $this->notSeeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 1]);
        $this->seeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 2]);
        $this->notSeeInDatabase('attribute_product_type', ['product_type_id' => 1, 'attribute_id' => 3]);
    }
}
