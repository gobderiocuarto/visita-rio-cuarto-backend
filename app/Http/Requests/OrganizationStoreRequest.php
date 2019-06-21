<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationStoreRequest extends FormRequest
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
            'category_id' => 'required|numeric',
            'name' => 'required',
            'slug' => 'required|unique:organizations,slug',
            'phone' => 'required|string',
            'email' => 'nullable|email|unique:organizations,email',
        ];
    }

    public function messages(){
        return [
            'name.required' => "El campo 'Nombre' es obligatorio",
            'phone.required' => "El campo 'Teléfono' es obligatorio",
        ];
    }
}