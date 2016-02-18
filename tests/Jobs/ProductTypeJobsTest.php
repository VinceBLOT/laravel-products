<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Speelpenning\Contracts\Products\ProductType;
use Speelpenning\Products\Events\ProductTypeWasDestroyed;
use Speelpenning\Products\Events\ProductTypeWasStored;
use Speelpenning\Products\Events\ProductTypeWasUpdated;
use Speelpenning\Products\Jobs\DestroyProductType;
use Speelpenning\Products\Jobs\StoreProductType;
use Speelpenning\Products\Jobs\UpdateProductType;

class ProductTypeJobsTest extends TestCase
{
    use DispatchesJobs;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('vendor:publish');
        $this->artisan('migrate:refresh');
    }

    protected function storeProductType($description)
    {
        return $this->dispatch(new StoreProductType($description));
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
}