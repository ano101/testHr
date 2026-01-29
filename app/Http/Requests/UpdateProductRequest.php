<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'gt:0'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'description' => ['sometimes', 'required', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Укажите название товара',
            'name.max' => 'Слишком длинное название (максимум :max символов)',
            'price.required' => 'Укажите цену',
            'price.numeric' => 'Цена должна быть числом',
            'price.gt' => 'Цена не может быть нулевой или отрицательной',
            'category_id.required' => 'Выберите категорию',
            'category_id.exists' => 'Такой категории не существует',
            'description.required' => 'Добавьте описание товара',
            'description.max' => 'Описание слишком длинное (не более :max символов)',
        ];
    }
}
