<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * يجب أن يكون المستخدم مسجلاً لتقديم بلاغ.
     */
    public function authorize(): bool
    {
        // middleware('auth') في المسار يعتني بهذا، لكن من الجيد التأكيد هنا أيضًا.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // عنوان البلاغ مطلوب
            'title' => ['required', 'string', 'max:255'],
            // الرابط الخارجي اختياري، ولكن إذا تم إدخاله، يجب أن يكون رابطًا صالحًا
            'external_url' => ['nullable', 'url', 'max:2048'],
            // النص المبلغ عنه مطلوب فقط إذا لم يتم تقديم رابط
            'reported_text' => ['nullable', 'string', 'required_without:external_url'],
            // ملاحظات المستخدم اختيارية
            'user_notes' => ['nullable', 'string', 'max:2000'],
            // الصور اختيارية، ولكن إذا تم رفعها، يجب أن تكون صورًا
            'images'   => ['nullable', 'array', 'max:3'], // مصفوفة اختيارية، بحد أقصى 3 صور
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // 2MB max per image
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
            'title.required' => 'حقل عنوان البلاغ مطلوب.',
            'title.max' => 'عنوان البلاغ يجب ألا يتجاوز 255 حرفًا.',
            'external_url.url' => 'الرجاء إدخال رابط صحيح (يجب أن يبدأ بـ http:// أو https://).',
            'reported_text.required_without' => 'يجب إدخال نص الخبر إذا لم يتم تقديم رابط.',
            'images.max' => 'لا يمكن رفع أكثر من 3 صور.',
            'images.*.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'images.*.mimes' => 'تنسيق الصورة غير مدعوم (المسموح: jpeg, png, jpg, gif, webp).',
            'images.*.max' => 'يجب ألا يتجاوز حجم الصورة الواحدة 2MB.',
        ];
    }
}