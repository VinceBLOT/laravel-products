<?php

use Speelpenning\Products\Attribute;
use Speelpenning\Products\AttributeValue;
use Speelpenning\Products\Exceptions\AttributeMustSupportValues;

class AttributeValueTest extends TestCase
{
    public function testItCanBeInstantiated()
    {
        $attribute = Attribute::instantiate('Gender', 'in');
        $attributeValue = AttributeValue::instantiate($attribute, 'Not specified');

        $this->assertInstanceOf(AttributeValue::class, $attributeValue);
        $this->assertEquals('Not specified', $attributeValue->value);
    }

    public function testItSquawksIfTheAttributeDoesNotSupportValues()
    {
        $attribute = Attribute::instantiate('Length', 'numeric');

        $this->setExpectedException(AttributeMustSupportValues::class);

        AttributeValue::instantiate($attribute, 12345);
    }
}
