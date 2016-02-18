<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Speelpenning\Contracts\Products\Attribute;
use Speelpenning\Products\AttributeValue;
use Speelpenning\Products\Events\AttributeValueWasDestroyed;
use Speelpenning\Products\Events\AttributeValueWasStored;
use Speelpenning\Products\Events\AttributeValueWasUpdated;
use Speelpenning\Products\Events\AttributeWasDestroyed;
use Speelpenning\Products\Http\Requests\UpdateAttributeValueRequest;
use Speelpenning\Products\Jobs\DestroyAttribute;
use Speelpenning\Products\Jobs\DestroyAttributeValue;
use Speelpenning\Products\Jobs\StoreAttribute;
use Speelpenning\Products\Jobs\StoreAttributeValue;
use Speelpenning\Products\Jobs\UpdateAttribute;
use Speelpenning\Products\Jobs\UpdateAttributeValue;

class AttributeValueJobsTest extends TestCase
{
    use DispatchesJobs;

    protected function createAttribute()
    {
        return $this->dispatch(new StoreAttribute('Some attribute', 'in'));
    }

    public function testStoreAttributeValue()
    {
        $attribute = $this->createAttribute();

        $this->expectsEvents(AttributeValueWasStored::class);

        $attributeValue = $this->dispatch(new StoreAttributeValue($attribute->id, 'Some value'));

        $this->assertInstanceOf(AttributeValue::class, $attributeValue);
        $this->assertNotNull($attributeValue->id);
        $this->assertEquals($attribute->id, $attributeValue->attribute_id);
        $this->assertEquals('Some value', $attributeValue->value);

        $this->seeInDatabase('attribute_values', [
            'attribute_id' => $attribute->id,
            'value' => 'Some value',
        ]);
    }

    public function testUpdateAttributeValue()
    {
        $attribute = $this->createAttribute();

        $attributeValue = $this->dispatch(new StoreAttributeValue($attribute->id, 'Some value'));

        $this->expectsEvents(AttributeValueWasUpdated::class);

        $attributeValue = $this->dispatch(new UpdateAttributeValue($attributeValue->id, 'Updated value'));

        $this->assertInstanceOf(AttributeValue::class, $attributeValue);
        $this->assertEquals('Updated value', $attributeValue->value);

        $this->seeInDatabase('attribute_values', [
            'id' => $attributeValue->id,
            'value' => 'Updated value',
        ]);
    }

    public function testDestroyAttributeValue()
    {
        $attribute = $this->createAttribute();

        $id = $this->dispatch(new StoreAttributeValue($attribute->id, 'Value to be destroyed'))->id;

        $this->expectsEvents(AttributeValueWasDestroyed::class);

        $attributeValue = $this->dispatch(new DestroyAttributeValue($id));

        $this->assertInstanceOf(AttributeValue::class, $attributeValue);

        $this->notSeeInDatabase('attribute_values', [
            'value' => 'Value to be destroyed',
        ]);
    }
}