<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    public function rules(): array
    {
        // الحصول على المستخدم الحالي من المسار
        $user = $this->route('user');

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id') // تجاهل المستخدم الحالي
            ],
            'password' => [
                'nullable', // كلمة المرور اختيارية عند التحديث
                'string',
                'confirmed',
                Password::min(8)
            ],
            'phone_number' => [
                'nullable',
                'string',
                'max:25',
                Rule::unique('users', 'phone_number')->ignore($user->user_id, 'user_id')->whereNotNull('phone_number') // تجاهل الحالي والتأكد من أنه ليس فارغًا
            ],
            'user_role' => ['required', 'string', Rule::in(['admin', 'editor', 'normal', 'pending_editor'])],
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,governorate_id'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string'],
        ];
    }

     public function messages(): array // يمكن استخدام نفس رسائل الإنشاء
    {
         return [
            'first_name.required' => 'حقل الاسم الأول مطلوب.',
            'last_name.required' => 'حقل الاسم الأخير مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'الرجاء إدخال بريد إلكتروني صحيح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            // لا نحتاج required لكلمة المرور هنا
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