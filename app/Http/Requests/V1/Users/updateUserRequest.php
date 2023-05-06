<?php

namespace App\Http\Requests\V1\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return empty(Auth::user()->type) && empty(Auth::user()->role) && Auth::id() == 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'password' => 'required',
            'address' => 'sometimes|string|nullable',
            'mobile' => 'sometimes|string|nullable',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال الاسم',
            'name.string' => 'الاسم من نوع نص',
            'email.required' => 'يرجى ادخال البريد الالكتروني',
            'email.email' => 'البريد يجب ان يكون من نوع ايميل',
            'email.unique' => 'يجب ان يكون البريد الالكتروني فريد ، البريد '.$this->email.' موجود مسبقا',
            'password.required' => 'يرجى ادخال كلمة المرور',
        ];
    }
}
