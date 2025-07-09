<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsageLogRequest extends FormRequest
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
        return [
            'userName' => 'required|string',
            'userPswd' => 'required|string',
            'StartTime' => 'required|date_format:m/Y',
            'EndTime' => 'required|date_format:m/Y',
        ];
    }
}
