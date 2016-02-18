<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Speelpenning\Contracts\Products\Repositories\ProductTypeRepository;
use Speelpenning\Products\Events\ProductWasDestroyed;
use Speelpenning\Products\Events\ProductWasStored;
use Speelpenning\Products\Events\ProductWasUpdated;
use Speelpenning\Products\Jobs\DestroyProduct;
use Speelpenning\Products\Jobs\StoreProduct;
use Speelpenning\Products\Jobs\UpdateProduct;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductType;

class ProductJobsTest extends TestCase
{
    use DispatchesJobs;

    /**
     * @return ProductType
     */
    protected function createProductType()
    {
        $productType = ProductType::instantiate('Testing equipment');
        app(ProductTypeRepository::class)->save($productType);
        return $productType;
    }

    public function testStoreProduct()
    {
        $this->expectsEvents(ProductWasStored::class);
        $productTypeId = $this->createProductType()->id;

        $product = $this->dispatch(new StoreProduct('123456', $productTypeId, 'Test dummy'));

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->id);
        $this->assertEquals('123456', $product->product_number);
        $this->assertEquals($productTypeId, $product->product_type_id);
        $this->assertEquals('Test dummy', $product->description);

        $this->seeInDatabase('products', [
            'product_type_id' => $productTypeId,
            'product_number' => '123456',
            'description' => 'Test dummy',
        ]);
    }

    public function testUpdateProduct()
    {
        $this->testStoreProduct();
        $this->expectsEvents(ProductWasUpdated::class);

        $id = 1;
        $description = 'Test dummy (special edition)';

        $product = $this->dispatch(new UpdateProduct($id, $description));

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($description, $product->description);

        $this->seeInDatabase('products', compact('id', 'description'));
    }

    public function testDestroyProduct()
    {
        $this->testStoreProduct();
        $this->expectsEvents(ProductWasDestroyed::class);

        $product = $this->dispatch(new DestroyProduct(1));

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->deleted_at);
    }
}
