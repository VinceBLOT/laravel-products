<?php

namespace Speelpenning\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductTypeRequest extends FormRequest
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
            'description' => ['required', 'string', 'unique:product_types,description,'.$this->route('product_type'), 'max:40'],
            'attributes' => ['sometimes' => 'array'],
        ];
    }
}