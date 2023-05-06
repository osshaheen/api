<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.email' => 'البريد يجب ان يكون من نوع ايميل',
            'email.exists' => 'البريد '.$this->email.' غير موجود',
            'password.required' => 'يرجى ادخال كلمة المرور'
        ];
    }
}
