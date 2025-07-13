<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // استيراد Rule

class UpdateGovernorateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         // السماح فقط للمدير بالتعديل
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // الحصول على كائن المحافظة من المسار باستخدام Route Model Binding
        $governorate = $this->route('governorate');

        return [
             'name' => [
                'required',
                'string',
                'max:150',
                 // التحقق من التفرد مع تجاهل السجل الحالي
                Rule::unique('governorates', 'name')->ignore($governorate->governorate_id, 'governorate_id')
            ],
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        // يمكن استخدام نفس الرسائل من StoreGovernorateRequest أو تخصيصها
         return [
            'name.required' => 'حقل اسم المحافظة مطلوب.',
            'name.string'   => 'يجب أن يكون اسم المحافظة نصًا.',
            'name.max'      => 'يجب ألا يتجاوز اسم المحافظة 150 حرفًا.',
            'name.unique'   => 'اسم المحافظة هذا موجود بالفعل.',
        ];
    }
}