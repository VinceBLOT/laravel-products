<?php

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Speelpenning\Products\Product;
use Speelpenning\Products\ProductNumber;
use Speelpenning\Products\ProductType;

class ProductTest extends TestCase
{
    public function testItCanBeInstantiated()
    {
        $product = Product::instantiate(
            ProductNumber::parse('123456'),
            ProductType::instantiate('Dummy'),
            'Some new product'
        );

        $this->assertInstanceOf(Product::class, $product);
        $this->assertInstanceOf(ProductNumber::class, $product->product_number);

        $this->assertEquals('123456', $product->product_number);
        $this->assertNull($product->productTypeId);
        $this->assertEquals('Some new product', $product->description);
    }
}
