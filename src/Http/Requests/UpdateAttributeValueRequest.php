<?php

namespace Speelpenning\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttributeValueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributeId'      => ['required', 'exists:attributes,id'],
            'attributeValueId' => ['required', 'exists:attribute_values,id'],
            'value'            => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Validate the class instance.
     *
     * @return void
     */
    public function validate()
    {
        $this->merge([
            'attributeId' => $this->route('attribute'),
            'attributeValueId' => $this->route('value'),
        ]);

        parent::validate();
    }
}