<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
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
            'section_id' => 'required|exists:sections,id',
            'paragraph_title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'special' => 'nullable|boolean',
            'date' => 'nullable|date',
            'phone_number' => 'nullable|digits_between:7,15',
            'email' => 'nullable|email|max:255',
            'work_time' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get the custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'section_id.required' => 'يجب تحديد القسم المرتبط.',
            'section_id.exists' => 'القسم المحدد غير موجود.',

            'paragraph_title.string' => 'يجب أن يكون العنوان نصاً.',
            'paragraph_title.max' => 'يجب ألا يزيد العنوان عن 255 حرفاً.',

            'description.required' => 'محتوى النص مطلوب.',
            'description.string' => 'يجب أن يكون المحتوى نصياً.',

            'location.string' => 'يجب أن يكون الموقع نصاً.',
            'location.max' => 'يجب ألا يزيد الموقع عن 255 حرفاً.',

            'special.boolean' => 'القيمة يجب أن تكون صحيحة أو خاطئة.',

            'date.date' => 'يجب أن يكون تاريخاً صالحاً.',

            'phone_number.digits_between' => 'رقم الهاتف يجب أن يكون بين 7 و 15 رقماً.',

            'email.email' => 'يجب أن يكون البريد الإلكتروني صحيحاً.',
            'email.max' => 'يجب ألا يزيد البريد الإلكتروني عن 255 حرفاً.',

            'work_time.integer' => 'وقت العمل يجب أن يكون عدداً صحيحاً.',
            'work_time.min' => 'وقت العمل يجب أن يكون صفراً أو أكبر.',
        ];
    }
}
