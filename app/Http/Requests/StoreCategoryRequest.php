<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Введите название категории',
            'name.unique' => 'Такая категория уже есть',
            'name.max' => 'Название слишком длинное (макс. :max символов)',
            'description.max' => 'Описание слишком длинное (макс. :max символов)',
        ];
    }
}
