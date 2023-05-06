<?php

namespace App\Http\Requests\V1\newsletter;

use Illuminate\Foundation\Http\FormRequest;

class newNewsLetterRequest extends FormRequest
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
            'email'=>'required|email|unique:news_letters,email'
        ];
    }
    public function messages()
    {
        return [
            'email.required'=>'يرجى اضافة بريد الكتروني',
            'email.email'=>'يرجى اضافة بريد الكتروني صالح',
            'email.unique'=>'البريد الالكتروني '.$this->email.' موجود مسبقا في القائمة البريدية'
        ];
    }
}
