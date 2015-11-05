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

        $data = [
            'product_number' => '123456',
            'product_type_id' => $this->createProductType()->id,
            'description' => 'Test dummy',
        ];

        $product = $this->dispatchFromArray(StoreProduct::class, $data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->id);
        $this->assertEquals($data['product_number'], $product->product_number);
        $this->assertEquals($data['product_type_id'], $product->product_type_id);
        $this->assertEquals($data['description'], $product->description);

        $this->seeInDatabase('products', $data);
    }

    public function testUpdateProduct()
    {
        $this->testStoreProduct();
        $this->expectsEvents(ProductWasUpdated::class);

        $data = [
            'id' => 1,
            'description' => 'Test dummy (special edition)',
        ];

        $product = $this->dispatchFromArray(UpdateProduct::class, $data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($data['description'], $product->description);

        $this->seeInDatabase('products', $data);
    }

    public function testDestroyProduct()
    {
        $this->testStoreProduct();
        $this->expectsEvents(ProductWasDestroyed::class);

        $product = $this->dispatchFromArray(DestroyProduct::class, ['id' => 1]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->deleted_at);
    }
}
