<?php

namespace App\Http\Requests\Auth;

use App\Models\User; // استيراد نموذج المستخدم
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // قد تحتاجAuth للتحقق من الصلاحيات إذا كان التسجيل مسموحاً لفئات معينة
use Illuminate\Support\Facades\Hash; // للتحقق من كلمة المرور
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule; // لاستخدام Rule::unique

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * التسجيل مسموح لأي زائر.
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
        return [
            // --- قواعد مخصصة لنموذج المستخدم الخاص بك ---
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class], // تحقق من تفرد الايميل
            'password' => ['required', 'confirmed', Rules\Password::defaults()], // تحقق من كلمة المرور وتطابقها

            // إضافة قواعد للحقول الاختيارية في التسجيل (إذا أردت طلبها هنا)
            // بناءً على مخطط قاعدة البيانات، يمكن إضافة رقم الهاتف والمحافظة هنا
            'phone_number' => ['nullable', 'string', 'max:25', 'unique:'.User::class], // تحقق من تفرد رقم الهاتف
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,governorate_id'], // تأكد أن المحافظة موجودة
            // date_of_birth و notes ليست ضرورية عند التسجيل، يمكن إضافتها لاحقاً في الملف الشخصي

            // --- إزالة قاعدة 'name' الأصلية ---
            // 'name' => ['required', 'string', 'max:255'], // <-- قم بحذف أو التعليق على هذا
            // ----------------------------------
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        // أضف رسائل باللغة العربية للحقول الجديدة
        return [
            'first_name.required' => 'حقل الاسم الأول مطلوب.',
            'last_name.required' => 'حقل الاسم الأخير مطلوب.',
            // ... أضف رسائل لباقي الحقول هنا ...
        ];
    }
}