<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // استيراد Rule

class StoreRegionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:150',
                // اسم المنطقة يجب أن يكون فريدًا فقط ضمن نفس المحافظة
                Rule::unique('regions')->where(function ($query) {
                    return $query->where('governorate_id', $this->input('governorate_id'));
                })
            ],
            'governorate_id' => [
                'required',
                'integer',
                'exists:governorates,governorate_id' // يجب أن تكون المحافظة موجودة
            ],
            'gps_coordinates' => [
                'nullable', // الحقل اختياري
                'string',
                'max:100',
                // يمكنك إضافة regex للتحقق من تنسيق الإحداثيات إذا أردت
                // 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل اسم المنطقة مطلوب.',
            'name.string' => 'يجب أن يكون اسم المنطقة نصًا.',
            'name.max' => 'يجب ألا يتجاوز اسم المنطقة 150 حرفًا.',
            'name.unique' => 'اسم المنطقة هذا موجود بالفعل في نفس المحافظة.',
            'governorate_id.required' => 'يجب اختيار المحافظة.',
            'governorate_id.integer' => 'معرف المحافظة غير صالح.',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة.',
            'gps_coordinates.string' => 'إحداثيات GPS يجب أن تكون نصًا.',
            'gps_coordinates.max' => 'يجب ألا تتجاوز إحداثيات GPS 100 حرف.',
            // 'gps_coordinates.regex' => 'تنسيق إحداثيات GPS غير صالح (مثال: 33.51,36.27).',
        ];
    }
}