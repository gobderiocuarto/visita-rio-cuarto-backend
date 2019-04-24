<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationUpdateRequest extends FormRequest
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
            'category_id' => 'required|numeric',
            'name' => 'required',
            'slug' => 'required|unique:organizations,slug,'. $this->organization,
            'email' => 'required|email|unique:organizations,email, '. $this->organization
        ];

        $rules['tags'] = 'tags_rule';

        //dd($rules);

        return $rules;
    }


    public function messages(){
        return [
            'name.required' => "El campo 'Nombre' es obligatorio",
            'tags_rule' => "Los tags sólo deben contener letras y números.",
        ];
    }
}
