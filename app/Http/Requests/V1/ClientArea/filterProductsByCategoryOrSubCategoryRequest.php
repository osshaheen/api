<?php

namespace App\Http\Requests\V1\ClientArea;

use Illuminate\Foundation\Http\FormRequest;

class filterProductsByCategoryOrSubCategoryRequest extends FormRequest
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
            'categoryIdList'=>'required|array',
            'categoryIdList.*'=>'required|numeric|exists:categories,id'
        ];
    }
    public function messages()
    {
        return [
            'categoryIdList.*.exists'=>'الصنف غير موجود في البرنامج'
        ];
    }
}
