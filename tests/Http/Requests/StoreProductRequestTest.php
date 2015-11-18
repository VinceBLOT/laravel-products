<?php

use Speelpenning\Products\Http\Requests\StoreProductRequest;

class StoreProductRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new StoreProductRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'productTypeId' => ['required', 'integer', 'exists:product_types,id'],
            'productNumber' => ['sometimes', 'required', 'string', 'unique:products,product_number', 'size:'.config('products.productNumber.length')],
            'description' => ['required', 'string', 'max:255'],
        ], $request->rules());
    }
}
