<?php

use Speelpenning\Products\Http\Requests\StoreAttributeValueRequest;

class StoreAttributeValueRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new StoreAttributeValueRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'attributeId' => ['required', 'exists:attributes,id'],
            'value' => ['required', 'string', 'max:255'],
        ], $request->rules());
    }
}