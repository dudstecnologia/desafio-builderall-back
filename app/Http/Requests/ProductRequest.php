<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'currency' => 'required',
            'image' => $this->method() == 'POST' ? 'required' : 'nullable'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $price = preg_replace('/[^0-9]/', '', $this->get('price'));

        $this->merge(compact('price'));
    }
}
