<?php

namespace App\Http\Requests\V1\coupons;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class newCouponRequest extends FormRequest
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
        if(isset($this->status)&&$this->status==0) {
            $start_date = 'required|date|date_format:Y-m-d';
            $end_date = 'required|date|date_format:Y-m-d|after:start_date';
        }
        return [
            'title'=>'required|string|unique:promo_codes,title',
            'status'=>'sometimes|in:0,1|nullable',
            'start_date'=>$start_date,
            'end_date'=>$end_date,
            'discount_percentage'=>'required|numeric|between:0,100'
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
            'title.required' => 'يرجى ادخال رمز الكوبون',
            'title.string' => 'اسم رمز الكوبون من نوع نص',
            'title.unique' => 'يرجى اختيار رمز اخر للكوبون لان الرمز المرسل مستخدم سابقا',
            'status.in' => 'الحالة فقط 1 او 0',
            'discount_percentage.required' => 'يرجى ادخال نسبة الخصم',
            'discount_percentage.numeric' => 'نسبة الخصم قيمة عددية',
            'discount_percentage.between' => 'نسبة الخصم قيمة عددية من 0 الى 100',
        ];
    }
}
