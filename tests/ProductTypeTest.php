<?php

use Speelpenning\Products\ProductType;

class ProductTypeTest extends TestCase
{
    public function testItCanBeInstantiated()
    {
        $productType = ProductType::instantiate('Personal Computer');
        $this->assertEquals('Personal Computer', $productType->description);
    }
}
