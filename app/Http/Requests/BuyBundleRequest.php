<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyBundleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subscription_id' => 'required|exists:subscriptions,id',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'userName' => 'required|string',
            'userPswd' => 'required|string',
        ];
    }
}
