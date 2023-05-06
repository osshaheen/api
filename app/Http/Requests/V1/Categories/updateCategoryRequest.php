<?php

namespace App\Http\Requests\V1\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return empty(Auth::user()->type) && empty(Auth::user()->role);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $admin_profit_percentage = '';
        if(isset($this->father_id)&&!empty($this->father_id)) {
            $admin_profit_percentage = 'required|numeric|between:0,100';
        }
        return [
            'name'=>'required|string|unique:categories,name,'.$this->category->id,
            'father_id'=>'sometimes|numeric|exists:categories,id|nullable',
            'admin_profit_percentage'=>$admin_profit_percentage,
            'asset_file'=>'sometimes|mimes:jpg,png,jpeg|nullable'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'يرجى ادخال اسم التصنيف',
            'name.string' => 'اسم التصنيف من نوع نص',
            'name.unique' => 'يرجى اختيار اسم اخر للتصنيف لان الاسم المرسل مستخدم سابقا',
            'father_id.numeric' => 'معرف تصنيف الاب ال id يجب ان يكون من نوع عدد',
            'father_id.exists' => 'التصنيف الأب غير موجود في البرنامج',
            'admin_profit_percentage.required' => 'يرجى ادخال نسبة ربح الأدمن ',
            'admin_profit_percentage.numeric' => 'نسبة ربح الأدمن يجب ان تكون قيمة عددية',
            'admin_profit_percentage.between' => 'نسبة ربح الأدمن يجب ان تكون قيمة عددية من 0 الى 100',
            'asset_file.mimes' => 'يرجى ارفاق صورة من نوع png,jpg,jpeg',
        ];
    }
}
