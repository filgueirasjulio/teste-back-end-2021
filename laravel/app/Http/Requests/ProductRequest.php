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
        $rules = [
            'name' => 'required|string',
            'price' => 'required|between:0,99.99',
            'weight' => 'required|between:0,99.99',
        ];

        if ($this->method == 'PUT') {
            $rules['image'] =  'mimes:jpeg,jpg,png,gif|required|max:10000';
        } else {
            $rules['image'] =  'mimes:jpeg,jpg,png,gif|nullable|max:10000';
        }

        return $rules;
    }
}
