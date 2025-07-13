<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل المستخدم:') }} {{ $user->first_name }} {{ $user->last_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.admin.sidebar')
                </div>
                 <div class="flex-grow">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                         {{-- Important: Add enctype for file uploads --}}
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="p-6 space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                    تعديل بيانات المستخدم
                                </h3>

                                @if ($errors->any())
                                    <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-md relative" role="alert">
                                        <strong class="font-bold block mb-1">حدث خطأ!</strong>
                                        <ul class="list-disc list-inside text-sm text-right">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Profile Image Upload Section (Similar to user's profile edit, but for Admin) --}}
                                <div x-data="{
                                    newImage: null,
                                    imageUrl: '{{ $user->profile_image_url }}',
                                    handleFileChange(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            this.newImage = URL.createObjectURL(file);
                                        }
                                    },
                                     removeImage: {{ old('remove_profile_image', false) ? 'true' : 'false' }} // Track if remove checkbox is checked
                                }">
                                    <label class="block text-sm font-medium text-gray-700 text-right mb-1">الصورة الشخصية (اختياري)</label>
                                    <div class="mt-1 flex flex-row-reverse items-center justify-start gap-4">
                                        {{-- Display current or new image preview --}}
                                        <img class="h-16 w-16 rounded-full object-cover flex-shrink-0 border-2 border-indigo-500"
                                             :src="newImage || imageUrl"
                                             alt="{{ $user->first_name }} {{ $user->last_name }}">

                                        {{-- Buttons Container --}}
                                        <div class="flex items-center space-x-4 space-x-reverse">
                                            <label for="profile_image" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                                <span>تغيير الصورة</span>
                                            </label>
                                             {{-- Hidden file input with Alpine event listener --}}
                                            <input id="profile_image" name="profile_image" type="file" class="sr-only" x-on:change="handleFileChange">

                                            {{-- Checkbox to Remove Image --}}
                                            @if($user->profile_image_path || $user->profile_image_url !== 'https://ui-avatars.com/api/?name=' . urlencode(trim(($user->attributes['first_name'] ?? '') . ' ' . ($user->attributes['last_name'] ?? ''))) . '&color=4F46E5&background=EEF2FF') {{-- Only show remove if there's a non-default image --}}
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="remove_profile_image" id="remove_profile_image" value="1" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500 me-1" x-model="removeImage"> {{-- me-1 for RTL --}}
                                                    <label for="remove_profile_image" class="text-sm text-gray-600">حذف الصورة</label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                     @error('profile_image', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>


                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                    {{-- First Name --}}
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأول <span class="text-red-600">*</span></label>
                                        <input id="first_name" name="first_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm" value="{{ old('first_name', $user->first_name) }}" required>
                                        @error('first_name', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Last Name --}}
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأخير <span class="text-red-600">*</span></label>
                                        <input id="last_name" name="last_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm" value="{{ old('last_name', $user->last_name) }}" required>
                                        @error('last_name', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 text-right mb-1">البريد الإلكتروني <span class="text-red-600">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('email', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Phone Number --}}
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700 text-right mb-1">رقم الهاتف (اختياري)</label>
                                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('phone_number', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Password (Optional) --}}
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 text-right mb-1">كلمة المرور (اتركها فارغة لعدم التغيير)</label>
                                        <input type="password" name="password" id="password"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('password', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Password Confirmation --}}
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 text-right mb-1">تأكيد كلمة المرور</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                    </div>
                                     {{-- User Role --}}
                                    <div>
                                        <label for="user_role" class="block text-sm font-medium text-gray-700 text-right mb-1">دور المستخدم <span class="text-red-600">*</span></label>
                                        <select name="user_role" id="user_role" required
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="" disabled>-- اختر دور --</option>
                                             @foreach ($roles as $role)
                                                 <option value="{{ $role }}" {{ old('user_role', $user->user_role) == $role ? 'selected' : '' }}>
                                                     @if($role === 'admin') مدير النظام
                                                     @elseif($role === 'editor') محرر محتوى
                                                     @elseif($role === 'normal') مستخدم عادي
                                                     @elseif($role === 'pending_editor') طلب محرر (معلق)
                                                     @endif
                                                 </option>
                                             @endforeach
                                        </select>
                                        @error('user_role', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Governorate --}}
                                    <div>
                                        <label for="governorate_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المحافظة (اختياري)</label>
                                        <select name="governorate_id" id="governorate_id"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- اختر محافظة --</option>
                                            @foreach ($governorates as $gov)
                                                <option value="{{ $gov->governorate_id }}" {{ old('governorate_id', $user->governorate_id) == $gov->governorate_id ? 'selected' : '' }}>
                                                    {{ $gov->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                         @error('governorate_id', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Date of Birth --}}
                                    <div class="md:col-span-2">
                                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 text-right mb-1">تاريخ الميلاد (اختياري)</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm text-right"
                                               style="color-scheme: light;">
                                        @error('date_of_birth', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Notes --}}
                                    <div class="md:col-span-2">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 text-right mb-1">ملاحظات (اختياري)</label>
                                        <textarea name="notes" id="notes" rows="4"
                                                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('notes', $user->notes) }}</textarea>
                                        @error('notes', 'updateProfileInformation') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                </div> {{-- End Grid --}}
                            </div> {{-- End Main Padding --}}

                            {{-- Form Footer with Buttons --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-start space-x-4 space-x-reverse">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                     <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    تحديث المستخدم
                                </button>
                                <a href="{{ route('admin.users.index') }}"
                                   class="inline-flex items-center justify-center px-5 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>