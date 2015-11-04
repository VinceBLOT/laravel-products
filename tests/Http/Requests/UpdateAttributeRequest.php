<?php

use Speelpenning\Products\Http\Requests\UpdateAttributeRequest;

class UpdateAttributeRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new UpdateAttributeRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'description' => ['required', 'string', 'unique:attributes,description'.$request->route('attribute'), 'max:40'],
            'unit_of_measurement' => ['string', 'max:20'],
        ], $request->rules());
    }
}
