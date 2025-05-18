<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaPageRequest extends FormRequest
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
            'type'=>'required|string',
            'media.*' => 'required|file|mimes:jpg,jpeg,png,mp4|max:20480',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'يجب كتابة نوع الملف.',
            'media.required' => 'يجب تحديد ملف الوسائط.',
            'media.file' => 'يجب أن يكون الملف صالحًا.',
            'media.mimes' => 'يجب أن يكون الملف من نوع jpg أو jpeg أو png أو mp4.',
            'media.max' => 'يجب ألا يتجاوز حجم الملف 20 ميجابايت.',
        ];
    }
}
