<?php

namespace App\Http\Requests\V1\sectionProducts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class updateSectionProductRequest extends FormRequest
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
        $start_date = '';
        $end_date = '';
        if(!isset($this->status)||$this->status==0) {
            $start_date = 'required|date|date_format:Y-m-d';
            $end_date = 'required|date|date_format:Y-m-d|after:start_date';
        }
        return [
            'product_id'=>'required|numeric|exists:products,id',
            'section_id'=>'required|numeric|in:0,2,3',
            'status'=>'sometimes|in:0,1|nullable',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
        ];
    }
    public function messages()
    {
        return [
            'start_date.required' => 'يرجى ادخال تاريخ بداية الكوبون',
            'start_date.date' => 'تاريخ بداية الكوبون من نوع تاريخ',
            'start_date.date_format' => 'صيغة بداية تاريخ الكوبون يوم - شهر - سنة',
            'end_date.required' => 'يرجى ادخال تاريخ نهاية الكوبون',
            'end_date.date' => 'تاريخ نهاية الكوبون من نوع تاريخ',
            'end_date.date_format' => 'صيغة نهاية تاريخ الكوبون يوم - شهر - سنة',
            'end_date.after' => 'أدخل نهاية تاريخ الكوبون بعد تاريخ بداية الكوبون',
            'product_id.required' => 'يرجى ادخال المنتج',
            'product_id.in' => 'معرف المنتج من نوع عدد',
            'product_id.exists' => 'معرف المنتج محذوف',
            'section_id.required' => 'يرجى ادخال القسم',
            'section_id.in' => 'القسم 0=best sellers او 2 = hot product  او 3 = best sellers2',
            'status.in' => 'الحالة فقط 1 او 0',
        ];
    }
}
