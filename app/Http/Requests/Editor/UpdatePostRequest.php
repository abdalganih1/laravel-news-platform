<?php

namespace App\Http\Requests\Editor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        // السماح للمحرر أو المدير بتعديل منشور
        // يمكنك إضافة تحقق إضافي هنا إذا كان المحرر يمكنه تعديل منشوراته فقط
        return Auth::check() && in_array(Auth::user()->user_role, ['editor', 'admin']);
    }

    public function rules(): array
    {
        $postId = $this->route('post')->post_id; // الحصول على ID المنشور الحالي

        return [
            'title' => ['required', 'string', 'max:255'],
            'text_content' => ['required', 'string'],
            'region_id' => ['nullable', 'integer', 'exists:regions,region_id'],
            'post_status' => ['required', 'string', Rule::in(['real', 'fake', 'pending_verification'])],
            'corrected_post_id' => [
                'nullable',
                'integer',
                // يجب أن يكون موجودًا وليس هو المنشور الحالي
                Rule::exists('posts', 'post_id')->whereNot('post_id', $postId),
                // 'required_if:post_status,fake'
                ],
            // صور جديدة للرفع
            'new_images'   => ['nullable', 'array', 'max:5'],
            'new_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            // مصفوفة IDs للصور المراد حذفها
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:post_images,image_id'], // تأكد من وجود الصورة قبل محاولة الحذف
             // نفس المنطق للفيديو
             'new_videos'   => ['nullable', 'array', 'max:2'],
             'new_videos.*' => ['file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo', 'max:20480'],
             'delete_videos' => ['nullable', 'array'],
             'delete_videos.*' => ['integer', 'exists:post_videos,video_id'],
        ];
    }

     public function messages(): array // يمكن استخدام نفس رسائل الإنشاء أو تخصيصها
    {
        return [
            'title.required' => 'عنوان المنشور مطلوب.',
            'title.max' => 'عنوان المنشور يجب ألا يتجاوز 255 حرفًا.',
            'text_content.required' => 'محتوى المنشور مطلوب.',
            'region_id.exists' => 'المنطقة المحددة غير موجودة.',
            'post_status.required' => 'يجب تحديد حالة المنشور.',
            'post_status.in' => 'حالة المنشور المحددة غير صالحة.',
            'corrected_post_id.integer' => 'معرف منشور التصحيح غير صالح.',
            'corrected_post_id.exists' => 'منشور التصحيح المحدد غير موجود أو هو نفس المنشور الحالي.',
            'new_images.max' => 'لا يمكن رفع أكثر من 5 صور جديدة.',
            'new_images.*.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'new_images.*.mimes' => 'تنسيق الصورة غير مدعوم.',
            'new_images.*.max' => 'يجب ألا يتجاوز حجم الصورة 2MB.',
            'delete_images.*.exists' => 'معرف الصورة المحدد للحذف غير صالح.',
            'new_videos.max' => 'لا يمكن رفع أكثر من مقطعي فيديو جديدين.',
            'new_videos.*.file' => 'الملف المرفوع يجب أن يكون فيديو.',
            'new_videos.*.mimetypes' => 'تنسيق الفيديو غير مدعوم.',
            'new_videos.*.max' => 'يجب ألا يتجاوز حجم الفيديو 20MB.',
            'delete_videos.*.exists' => 'معرف الفيديو المحدد للحذف غير صالح.',
        ];
    }
}