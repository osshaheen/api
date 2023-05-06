<?php

namespace App\Http\Requests\V1\category_properties;

use App\Models\CategoryProperty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class newCategoryPropertyRequest extends FormRequest
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
        $existed_property = CategoryProperty::where('category_id',$this->category_id)
            ->where('property_name',$this->property_name)
            ->first();
        $property_name_role = '';
        if($existed_property){
            $property_name_role = '|unique:category_properties,property_name';
        }
        return [
            'category_id'=>'required|numeric|exists:categories,id',
            'property_name'=>'required|string'.$property_name_role
        ];
    }
    public function messages()
    {
        return [
            'category_id.required'=>'يرجى اضافة التصنيف',
            'category_id.numeric'=>'معرف التصنيف ال category_id يجب ان يكون قيمة عددية',
            'category_id.exists'=>'يرجى اضافة تصنيف موجود في قاعدة البيانات',
            'property_name.required'=>'يرجى اضافة الخاصية',
            'property_name.string'=>'قيمة الخاصية يجب ان تكون نص',
            'property_name.unique'=>'قيمة الخاصية موجودة مسبقا لنفس التصنيف',
        ];
    }
}
