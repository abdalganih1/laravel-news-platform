<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة مستخدم جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.admin.sidebar') {{-- Sidebar remains the same --}}
                </div>
                 <div class="flex-grow">
                    {{-- Form wrapped in a styled card --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <form action="{{ route('admin.users.store') }}" method="POST">
                            @csrf
                            <div class="p-6 space-y-6"> {{-- Increased padding and vertical spacing --}}
                                <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                    بيانات المستخدم الأساسية
                                </h3>

                                {{-- Display Validation Errors (Styled) --}}
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

                                {{-- Main Form Grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                                    {{-- First Name --}}
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأول <span class="text-red-600">*</span></label>
                                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                        @error('first_name') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Last Name --}}
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأخير <span class="text-red-600">*</span></label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                        @error('last_name') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 text-right mb-1">البريد الإلكتروني <span class="text-red-600">*</span></label>
                                        {{-- Added ltr:text-left for better input experience --}}
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('email') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Phone Number --}}
                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700 text-right mb-1">رقم الهاتف (اختياري)</label>
                                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('phone_number') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Password --}}
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 text-right mb-1">كلمة المرور <span class="text-red-600">*</span></label>
                                        <input type="password" name="password" id="password" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                        @error('password') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Password Confirmation --}}
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 text-right mb-1">تأكيد كلمة المرور <span class="text-red-600">*</span></label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                    </div>

                                    {{-- User Role --}}
                                    <div>
                                        <label for="user_role" class="block text-sm font-medium text-gray-700 text-right mb-1">دور المستخدم <span class="text-red-600">*</span></label>
                                        <select name="user_role" id="user_role" required
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="" disabled {{ old('user_role') ? '' : 'selected' }}>-- اختر دور --</option>
                                             @foreach ($roles as $role)
                                                 <option value="{{ $role }}" {{ old('user_role') == $role ? 'selected' : '' }}>
                                                     @if($role === 'admin') مدير النظام
                                                     @elseif($role === 'editor') محرر محتوى
                                                     @else مستخدم عادي
                                                     @endif
                                                 </option>
                                             @endforeach
                                        </select>
                                        @error('user_role') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Governorate --}}
                                    <div>
                                        <label for="governorate_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المحافظة (اختياري)</label>
                                        <select name="governorate_id" id="governorate_id"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- اختر محافظة --</option>
                                            @foreach ($governorates as $gov)
                                                <option value="{{ $gov->governorate_id }}" {{ old('governorate_id') == $gov->governorate_id ? 'selected' : '' }}>
                                                    {{ $gov->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                         @error('governorate_id') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Date of Birth --}}
                                    <div class="md:col-span-2"> {{-- Span full width on medium screens --}}
                                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 text-right mb-1">تاريخ الميلاد (اختياري)</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                               class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 sm:text-sm text-right"
                                               style="color-scheme: light;"> {{-- Hint for browser date picker styling --}}
                                        @error('date_of_birth') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Notes --}}
                                    <div class="md:col-span-2">
                                        <label for="notes" class="block text-sm font-medium text-gray-700 text-right mb-1">ملاحظات (اختياري)</label>
                                        <textarea name="notes" id="notes" rows="4"
                                                  class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('notes') }}</textarea>
                                        @error('notes') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                </div> {{-- End Grid --}}
                            </div> {{-- End Main Padding --}}

                            {{-- Form Footer with Buttons --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-start space-x-4 space-x-reverse">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    حفظ المستخدم
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