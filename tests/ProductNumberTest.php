<?php

use Speelpenning\Products\ProductNumber;

class ProductNumberTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        config([
            'products.productNumber.autoIncrements' => true,
            'products.productNumber.length' => 6,
        ]);
    }

    public function testItParsesProductNumbersAndCastsToString()
    {
        $productNumber = ProductNumber::parse('123456');

        $this->assertInstanceOf(ProductNumber::class, $productNumber);
        $this->assertEquals('123456', (string)$productNumber);
    }

    public function testTheLengthCanBeConfigured()
    {
        $this->assertEquals(config('products.productNumber.length'), ProductNumber::parse('123456')->length());
    }

    public function testItIsSelfValidating()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        new ProductNumber('invalid value');
    }

    public function testItRejectsLeadingZeroes()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        ProductNumber::parse('012345');
    }

    public function testItCanGenerateTheNextProductNumber()
    {
        $next = ProductNumber::parse('123456')->next();

        $this->assertInstanceOf(ProductNumber::class, $next);
        $this->assertEquals('123457', (string)$next);
    }

    public function testAutoIncrementingCanBeDisabled()
    {
        config(['products.productNumber.autoIncrements' => false]);

        $this->assertNull(ProductNumber::parse('123456')->next());
    }
}
