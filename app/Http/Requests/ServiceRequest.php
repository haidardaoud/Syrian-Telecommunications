<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0'
        ];

        // // إضافة شرط فريد للتحديث (تجاهل السجل الحالي)
        // if ($this->isMethod('PUT')) {
        //     $rules['name'] .= '|unique:services,name,' . $this->route('id');
        // } else {
        //     $rules['name'] .= '|unique:services,name';
        // }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الخدمة مطلوب',
            'name.unique' => 'هذه الخدمة موجودة مسبقاً',
            'price.min' => 'السعر يجب أن يكون رقم موجب'
        ];
    }
}
