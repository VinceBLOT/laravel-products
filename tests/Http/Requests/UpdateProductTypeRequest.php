<?php

use Speelpenning\Products\Http\Requests\UpdateProductTypeRequest;

class UpdateProductTypeRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new UpdateProductTypeRequest();

        $this->assertEquals([
            'description' => ['required', 'string', 'unique:product_types,description,'.$request->route('product_type')],
        ]);
    }
}