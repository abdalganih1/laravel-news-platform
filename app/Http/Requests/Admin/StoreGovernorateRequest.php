<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreGovernorateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // السماح فقط للمدير بالإنشاء
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
            'name' => [
                'required', // الحقل مطلوب
                'string',   // يجب أن يكون نصًا
                'max:150',  // الحد الأقصى للحروف
                'unique:governorates,name' // يجب أن يكون فريدًا في جدول المحافظات
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
            'name.required' => 'حقل اسم المحافظة مطلوب.',
            'name.string'   => 'يجب أن يكون اسم المحافظة نصًا.',
            'name.max'      => 'يجب ألا يتجاوز اسم المحافظة 150 حرفًا.',
            'name.unique'   => 'اسم المحافظة هذا موجود بالفعل.',
        ];
    }
}