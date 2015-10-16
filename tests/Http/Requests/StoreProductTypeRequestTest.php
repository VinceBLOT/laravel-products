<?php

use Speelpenning\Products\Http\Requests\StoreProductTypeRequest;

class StoreProductTypeRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new StoreProductTypeRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'description' => ['required', 'string', 'unique:product_types', 'max:40'],
        ], $request->rules());
    }
}