<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'page_title' => 'required|string|max:255',
        ];
    }


    public function messages(): array
    {
        return [
            'page_title.required' => 'عنوان الصفحة مطلوب.',
            'page_title.string' => 'يجب أن يكون العنوان نصاً.',
            'page_title.max' => 'يجب ألا يزيد العنوان عن 255 حرفاً.',
           
        ];
    }
}

