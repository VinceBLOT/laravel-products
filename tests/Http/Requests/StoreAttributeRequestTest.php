<?php

use Speelpenning\Products\Http\Requests\StoreAttributeRequest;

class StoreAttributeRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new StoreAttributeRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'description' => ['required', 'string', 'unique:attributes', 'max:40'],
            'type' => ['required', 'string', 'max:20'],
        ], $request->rules());
    }
}