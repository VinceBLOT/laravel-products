<?php

use Speelpenning\Products\Http\Requests\UpdateAttributeValueRequest;

class UpdateAttributeValueRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new UpdateAttributeValueRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'attributeId'      => ['required', 'exists:attributes,id'],
            'attributeValueId' => ['required', 'exists:attribute_values,id'],
            'value'            => ['required', 'string', 'max:255'],
        ], $request->rules());
    }
}