<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        $id = auth()->user()->id;
        
        return [
            'name' => 'required|string',
            'email' => "required|email|unique:users,email,{$id},id",
            'password' => 'required|string|min:8|max:30|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).+$/'
        ];
    }
}
