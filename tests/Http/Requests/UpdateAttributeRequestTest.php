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

            'maxlength' => ['integer', 'between:1,255'],
            'autocomplete' => ['string', 'in:off'],
            'placeholder' => ['string', 'max:255'],
            'pattern' => ['string', 'max:255'],

            'min' => ['numeric'],
            'max' => ['numeric'],
            'step' => ['numeric'],
            'unitOfMeasurement' => ['string', 'max:20'],
        ], $request->rules());
    }
}
