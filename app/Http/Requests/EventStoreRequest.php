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
            'category_id' => 'required|numeric',
            'title' => 'required',
            'slug' => 'required|unique:events,slug',
            'summary' => 'required|string|max:250',
        ];
    }

    public function messages(){
        return [
            'category_id.required' => "El campo 'Categoría' es obligatorio",
            'title.required' => "El campo 'Título' es obligatorio",
            'slug.unique' => "El titulo elegido ya existe",
            'summary.required' => "El campo 'Resumen' es obligatorio",
            'summary.max' => "El campo 'Resumen' no debe superar los :max caracteres",
        ];
    }
}