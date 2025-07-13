<?php

namespace App\Http\Requests\Editor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewClaimRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // السماح للمحرر أو المدير بمراجعة البلاغ
        return Auth::check() && in_array(Auth::user()->user_role, ['editor', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // قرار المحرر بشأن حالة المنشور
            'post_status' => [
                'required',
                'string',
                Rule::in(['real', 'fake']), // يجب أن يكون القرار إما 'حقيقي' أو 'مزيف'
            ],
            // ملاحظات المحرر (اختيارية لكن موصى بها)
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            // معرف المنشور المصحح (اختياري ويطبق فقط إذا كان المنشور مزيفاً)
            'corrected_post_id' => [
                'nullable', // يمكن تركه فارغًا
                'integer',
                // يجب أن يكون موجودًا في جدول posts (باستثناء المنشور الحالي نفسه)
                Rule::exists('posts', 'post_id')->whereNot('post_id', $this->claim->post_id),
                // مطلوب فقط إذا تم تحديد المنشور كـ fake (لكن التحقق منه يتم في المتحكم لضمان أنه real)
                // 'required_if:post_status,fake', // يمكن إضافة هذا لفرض الإدخال من الواجهة
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
        return [
            'post_status.required' => 'يجب تحديد حالة المنشور (حقيقي أو مزيف).',
            'post_status.in' => 'الحالة المحددة للمنشور غير صالحة.',
            'admin_notes.string' => 'ملاحظات المحرر يجب أن تكون نصًا.',
            'admin_notes.max' => 'ملاحظات المحرر يجب ألا تتجاوز 1000 حرف.',
            'corrected_post_id.integer' => 'معرف المنشور المصحح غير صالح.',
            'corrected_post_id.exists' => 'المنشور المصحح المحدد غير موجود.',
            // 'corrected_post_id.required_if' => 'يجب تحديد منشور تصحيح إذا تم تحديد المنشور الحالي كمزيف.',
        ];
    }
}