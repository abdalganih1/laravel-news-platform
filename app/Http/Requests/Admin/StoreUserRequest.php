<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // استيراد قاعدة كلمة المرور
use Illuminate\Validation\Rule; // استيراد Rule

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                'confirmed', // يتطلب حقل 'password_confirmation' في النموذج
                Password::min(8) // تطبيق قواعد كلمة المرور الافتراضية (طول، تعقيد - يمكن تخصيصها)
                       // ->mixedCase()
                       // ->numbers()
                       // ->symbols()
                       // ->uncompromised(),
            ],
             // password_confirmation يتم التحقق منه تلقائيًا بواسطة 'confirmed'
            'phone_number' => ['nullable', 'string', 'max:25', 'unique:users,phone_number'],
            'user_role' => ['required', 'string', Rule::in(['admin', 'editor', 'normal'])],
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,governorate_id'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'], // التأكد من تنسيق التاريخ
            'notes' => ['nullable', 'string'],
        ];
    }

     public function messages(): array // رسائل مخصصة باللغة العربية
    {
        return [
            'first_name.required' => 'حقل الاسم الأول مطلوب.',
            'last_name.required' => 'حقل الاسم الأخير مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'الرجاء إدخال بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone_number.unique' => 'رقم الهاتف هذا مستخدم بالفعل.',
            'user_role.required' => 'حقل دور المستخدم مطلوب.',
            'user_role.in' => 'قيمة دور المستخدم غير صالحة.',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة.',
            'date_of_birth.date_format' => 'تنسيق تاريخ الميلاد يجب أن يكون YYYY-MM-DD.',
        ];
    }
}