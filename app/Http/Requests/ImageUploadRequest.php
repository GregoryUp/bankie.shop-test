<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'main_slider' => 'required|array|min:1|max:5',
            'main_slider.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ];
    }

    public function messages() {
        return [
            'main_slider.required' => 'Ни один файл не был передан',
            'main_slider.array' => 'Неправильный формат данных',
            'main_slider.min' => 'Необходимо загрузить хотя бы один файл',
            'main_slider.max' => 'Лимит количества картинок превышен',
            'main_slider.*.image' => 'Файл должен быть изображением.',
            'main_slider.*.mimes' => 'Поддерживаемые форматы изображений: jpeg, png, jpg, gif.',
            'main_slider.*.max' => 'Максимальный размер изображения 5MB.',
        ];
    }
}
