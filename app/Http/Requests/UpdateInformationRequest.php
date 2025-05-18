<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInformationRequest extends FormRequest
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
        'title' => 'sometimes|string|max:255',
        'type' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'media' => 'nullable|array',
        'media.*' => 'file|mimes:jpg,jpeg,png,mp4',
        'media_type' => 'nullable|string|in:image,video',
    ];
    }
    public function messages(): array
{
    return [
        'title.string' => 'يجب أن يكون العنوان نصاً.',
        'title.max' => 'يجب ألا يزيد العنوان عن 255 حرفاً.',

        'type.string' => 'يجب أن يكون نوع الفقرة نصاً.',
        'type.max' => 'يجب ألا يزيد نوع الفقرة عن 255 حرفاً.',

        'description.string' => 'يجب أن يكون الوصف نصاً.',

        'media.array' => 'يجب أن تكون الوسائط على شكل مصفوفة.',
        'media.*.file' => 'يجب أن تكون كل وسيلة ملفًا صالحًا.',
        'media.*.mimes' => 'يجب أن تكون الوسائط من نوع jpg أو jpeg أو png أو mp4 فقط.',

        'media_type.string' => 'يجب أن يكون نوع الوسائط نصاً.',
        'media_type.in' => 'نوع الوسائط يجب أن يكون إما "image" أو "video".',
    ];
}

}
