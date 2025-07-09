<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SebastianBergmann\Type\TrueType;

class LoginRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'user_name' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ];
    // }
    // public function messages(): array
    // {
    //     return [
    //         'user_name.required_without' => 'يجب إدخال اسم المستخدم .',
    //         'password.required' => 'يجب إدخال كلمة المرور.',
    //         'password.min' => 'يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.',
    //     ];
    // }
    // public function validatedCredentials()
    // {
    //     return $this->only(['user_name', 'password']);
    // }
    public function rules()
    {
        return [
            'userName' => 'required|string', // تغيير من user_name إلى userName
            'userPswd' => 'required|string'  // تغيير من password إلى userPswd
        ];
    }

    public function credentials()
    {
        return [
            'user_name' => $this->input('userName'), // تحويل userName إلى user_name
            'password' => $this->input('userPswd')    // تحويل userPswd إلى password
        ];
    }

}
