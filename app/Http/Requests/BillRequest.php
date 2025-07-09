<?php
// app/Http/Requests/BillRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phon' => 'required|numeric|digits_between:9,12'
        ];
    }

    public function messages()
    {
        return [
            'phon.required' => 'رقم الهاتف مطلوب',
            'phon.numeric' => 'يجب أن يكون الرقم أرقام فقط',
            'phon.digits_between' => 'يجب أن يكون الرقم بين 9 إلى 12 رقم'
        ];
    }
}
