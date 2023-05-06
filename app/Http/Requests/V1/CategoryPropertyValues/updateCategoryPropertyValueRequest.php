<?php

namespace App\Http\Requests\V1\CategoryPropertyValues;

use App\Models\CategoryPropertyValue;
use Illuminate\Foundation\Http\FormRequest;

class updateCategoryPropertyValueRequest extends FormRequest
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
        $value_rules = '';
        $existed_value = CategoryPropertyValue::where('property_id',$this->property_id)
            ->where('product_id',$this->product_id)
            ->first();
        if(!empty($existed_value) && $existed_value->id != $this->categoryPropertyValue->id){
            $value_rules = '|unique:category_property_values,value';
        }
        return [
            'property_id'=>'required|numeric|exists:category_properties,id',
            'product_id'=>'required|numeric|exists:products,id',
            'value'=>'required'.$value_rules
        ];
    }
    public function messages()
    {
        return [
            'property_id.required'=>'يرجى اضافة الخاصية',
            'property_id.exists'=>'الخاصية المدخلة غير موجودة',
            'product_id.required'=>'يرجى اضافة المنتج',
            'product_id.exists'=>'المنتج المدخل غير موجود',
            'value.required'=>'ادخل قيمة الخاصية',
            'value.unique'=>'الخاصية مكررة لنفس المنتج',
        ];
    }
}
