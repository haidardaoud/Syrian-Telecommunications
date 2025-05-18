<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
            'section_title' => 'required|string|max:255',
            'page_id' => 'required|exists:pages,id',
        ];
    }

     public function messages(): array
    {
        return [
            'section_title.required' => 'عنوان القسم مطلوب.',
            'section_title.string' => 'يجب أن يكون العنوان نصاً.',
            'section_title.max' => 'يجب ألا يزيد العنوان عن 255 حرفاً.',
            'page_id.required' => 'يجب تحديد الصفحة المرتبطة.',
            'page_id.exists' => 'الصفحة المحددة غير موجودة.',
        ];
    }
}
