<?php

use Speelpenning\Products\Http\Requests\UpdateProductRequest;

class UpdateProductRequestTest extends TestCase
{
    public function testValidationRules()
    {
        $request = new UpdateProductRequest();

        $this->assertTrue($request->authorize());
        $this->assertEquals([
            'description' => ['required', 'string', 'max:255'],
        ], $request->rules());
    }
}
