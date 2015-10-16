<?php

use Speelpenning\Products\Attribute;

class AttributeTest extends TestCase
{
    public function testItCanBeInstantiated()
    {
        $attribute = Attribute::instantiate('Length', 'numeric');

        $this->assertEquals('Length', $attribute->description);
        $this->assertEquals('numeric', $attribute->type);
    }

    public function testItExposesSupportedTypes()
    {
        $this->assertEquals(['string', 'numeric', 'in'], Attribute::getAllowedTypes());
    }

    public function testStringTypeDoesNotSupportValues()
    {
        $this->assertFalse(Attribute::instantiate('Author', 'string')->supportsValues());
    }

    public function testNumericTypeDoesNotSupportValues()
    {
        $this->assertFalse(Attribute::instantiate('Length', 'numeric')->supportsValues());
    }

    public function testInTypeSupportsValues()
    {
        $this->assertTrue(Attribute::instantiate('Author', 'in')->supportsValues());
    }

}