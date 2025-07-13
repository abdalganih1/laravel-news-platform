<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRegionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    public function rules(): array
    {
        // الحصول على المنطقة الحالية من المسار
        $region = $this->route('region');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                 // اسم المنطقة يجب أن يكون فريدًا ضمن نفس المحافظة، مع تجاهل المنطقة الحالية
                 Rule::unique('regions')->where(function ($query) {
                     return $query->where('governorate_id', $this->input('governorate_id'));
                 })->ignore($region->region_id, 'region_id') // تجاهل السجل الحالي
            ],
            'governorate_id' => [
                'required',
                'integer',
                'exists:governorates,governorate_id'
            ],
            'gps_coordinates' => [
                'nullable',
                'string',
                'max:100',
                // 'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?),[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
        ];
    }

    public function messages(): array
    {
         // يمكن استخدام نفس الرسائل من StoreRegionRequest
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