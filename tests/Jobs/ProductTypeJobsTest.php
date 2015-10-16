<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Speelpenning\Contracts\Products\ProductType;
use Speelpenning\Products\Events\ProductTypeWasDestroyed;
use Speelpenning\Products\Events\ProductTypeWasStored;
use Speelpenning\Products\Events\ProductTypeWasUpdated;
use Speelpenning\Products\Jobs\DestroyProductType;
use Speelpenning\Products\Jobs\StoreProductType;
use Speelpenning\Products\Jobs\UpdateProductType;

class ProductTypeJobsTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions, DispatchesJobs;

    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    protected function storeProduct($description)
    {
        return $this->dispatchFromArray(StoreProductType::class, compact('description'));
    }


    public function testStoreProductType()
    {
        $description = 'Personal Computer';
        $this->expectsEvents(ProductTypeWasStored::class);

        $productType = $this->storeProduct($description);

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertNotNull($productType->id);
        $this->assertEquals($description, $productType->description);

        $this->seeInDatabase('product_types', compact('description'));
    }

    public function testUpdateProductType()
    {
        $id = $this->storeProduct('Description to be updated')->id;
        $description = 'Updated description';

        $this->expectsEvents(ProductTypeWasUpdated::class);

        $productType = $this->dispatchFromArray(UpdateProductType::class, compact('id', 'description'));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertEquals($description, $productType->description);

        $this->seeInDatabase('product_types', compact('description'));
    }

    public function testDestroyProductType()
    {
        $id = $this->storeProduct('Product type to be destroyed')->id;
        $this->expectsEvents(ProductTypeWasDestroyed::class);

        $productType = $this->dispatchFromArray(DestroyProductType::class, compact('id'));

        $this->assertInstanceOf(ProductType::class, $productType);
        $this->assertNotNull($productType->deleted_at);
    }
}