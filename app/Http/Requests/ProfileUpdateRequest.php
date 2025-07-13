<?php

namespace App\Http\Requests;

use App\Models\User; // استيراد نموذج المستخدم
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // قواعد Breeze الأصلية (قد تحتاج لتعديل 'name')
            // 'name' => ['string', 'max:255'], // إذا لم تستخدم first/last name
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->user_id, 'user_id')],

            // --- أضف قواعد حقول المستخدم المخصصة ---
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone_number' => ['nullable', 'string', 'max:25', Rule::unique(User::class)->ignore($this->user()->user_id, 'user_id')->whereNotNull('phone_number')],
            'governorate_id' => ['nullable', 'integer', 'exists:governorates,governorate_id'],
            'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
            'notes' => ['nullable', 'string'],
            // --------------------------------------

            // --- أضف قواعد ملف الصورة الشخصية ---
        'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // 'profile_image' هو اسم حقل الملف في النموذج
            // -----------------------------------
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
         // أضف رسائل مخصصة لقواعد الصورة الجديدة
        return [
            'profile_image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'profile_image.mimes' => 'تنسيق الصورة غير مدعوم (المسموح: jpeg, png, jpg, gif, webp).',
            'profile_image.max' => 'يجب ألا يتجاوز حجم الصورة 2MB.',
            // يمكنك إضافة رسائل للحقول الأخرى هنا إذا لم تكن معرفة في Form Requests أخرى
        ];
    }
}