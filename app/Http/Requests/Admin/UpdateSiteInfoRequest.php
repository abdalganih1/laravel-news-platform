<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateSiteInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // فقط المدير يمكنه تعديل معلومات الموقع
        return Auth::check() && Auth::user()->user_role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'], // قد لا تحتاج لتغيير العنوان الرئيسي
            'content' => ['nullable', 'string'], // المحتوى الرئيسي (مثل نص "حولنا")
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'website_url' => ['nullable', 'string', 'url', 'max:255'], // التحقق من كونه رابطًا صالحًا
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.max' => 'عنوان المحتوى يجب ألا يتجاوز 255 حرفًا.',
            'contact_phone.max' => 'رقم هاتف الاتصال يجب ألا يتجاوز 50 حرفًا.',
            'contact_email.email' => 'الرجاء إدخال بريد إلكتروني صحيح للاتصال.',
            'contact_email.max' => 'بريد الاتصال الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
            'website_url.url' => 'الرجاء إدخال رابط موقع إلكتروني صحيح.',
            'website_url.max' => 'رابط الموقع الإلكتروني يجب ألا يتجاوز 255 حرفًا.',
        ];
    }
}