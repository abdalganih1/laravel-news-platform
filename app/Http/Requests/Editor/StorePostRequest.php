<?php

namespace App\Http\Requests\Editor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // السماح للمحرر أو المدير بإنشاء منشور
        return Auth::check() && in_array(Auth::user()->user_role, ['editor', 'admin']);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'text_content' => ['required', 'string'],
            'region_id' => ['nullable', 'integer', 'exists:regions,region_id'],
            // حالة المنشور يحددها المحرر عند الإنشاء
            'post_status' => ['required', 'string', Rule::in(['real', 'fake', 'pending_verification'])],
            'correction_method' => ['nullable', 'string', 'in:existing,new', 'required_if:post_status,fake'],
            'corrected_post_id' => [
                'nullable',
                'integer',
                'exists:posts,post_id',
                'required_if:correction_method,existing'
            ],
            'new_correction_title' => ['nullable', 'string', 'max:255', 'required_if:correction_method,new'],
            'new_correction_content' => ['nullable', 'string', 'required_if:correction_method,new'],
            // قواعد للصور (مثال)
            'images'   => ['nullable', 'array', 'max:5'], // مصفوفة اختيارية، بحد أقصى 5 صور
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'], // كل عنصر يجب أن يكون صورة وبالتنسيقات والحجم المسموح
            // قواعد للفيديو (مثال)
            'videos'   => ['nullable', 'array', 'max:2'], // مصفوفة اختيارية، بحد أقصى مقطعي فيديو
            'videos.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo', 'max:20480'], // كل عنصر يجب أن يكون فيديو وبالتنسيقات والحجم المسموح (20MB)

        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان المنشور مطلوب.',
            'title.max' => 'عنوان المنشور يجب ألا يتجاوز 255 حرفًا.',
            'text_content.required' => 'محتوى المنشور مطلوب.',
            'region_id.exists' => 'المنطقة المحددة غير موجودة.',
            'post_status.required' => 'يجب تحديد حالة المنشور.',
            'post_status.in' => 'حالة المنشور المحددة غير صالحة.',
            'corrected_post_id.integer' => 'معرف منشور التصحيح غير صالح.',
            'corrected_post_id.exists' => 'منشور التصحيح المحدد غير موجود.',
            // 'corrected_post_id.required_if' => 'يجب تحديد منشور تصحيح إذا كانت الحالة "مزيف".',
            'images.max' => 'لا يمكن رفع أكثر من 5 صور.',
            'images.*.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'images.*.mimes' => 'تنسيق الصورة غير مدعوم (المسموح: jpeg, png, jpg, gif, webp).',
            'images.*.max' => 'يجب ألا يتجاوز حجم الصورة 2MB.',
            'videos.max' => 'لا يمكن رفع أكثر من مقطعي فيديو.',
            'videos.*.file' => 'يجب أن يكون الملف فيديو.',
            'videos.*.mimetypes' => 'تنسيق الفيديو غير مدعوم (المسموح: mp4, mov, avi).',
            'videos.*.max' => 'يجب ألا يتجاوز حجم الفيديو 20MB.',
        ];
    }
}