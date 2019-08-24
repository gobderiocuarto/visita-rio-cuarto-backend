<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
            'title' => 'required',
            'slug' => 'required|unique:events,slug',
            'summary' => 'required|string|max:500',
        ];
    }

    public function messages(){
        return [
            'category_id.required' => "El campo 'Categoría' es obligatorio",
            'title.required' => "El campo 'Título' es obligatorio",
            'slug.unique' => "El titulo elegido ya existe",
            'summary.required' => "El campo 'Información principal' es obligatorio",
            'summary.max' => "El campo 'Información principal' no debe superar los :max caracteres",
        ];
    }
}