<?php

namespace Speelpenning\Products\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'productTypeId' => ['required', 'integer', 'exists:product_types,id'],
            'productNumber' => ['sometimes', 'required', 'string', 'unique:products,product_number', 'size:'.config('products.productNumber.length')],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
