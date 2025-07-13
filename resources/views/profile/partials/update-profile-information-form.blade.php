<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 text-right"> {{-- Added text-right --}}
            {{ __('معلومات الملف الشخصي') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 text-right"> {{-- Added text-right --}}
            {{ __("قم بتحديث معلومات ملفك الشخصي وعنوان بريدك الإلكتروني.") }}
        </p>
    </header>

     {{-- Add enctype for file upload --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

     {{-- Add enctype for file upload --}}
 <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

         {{-- Profile Image Upload Section with Alpine.js for Preview --}}
        <div x-data="{
            newImage: null,
            imageUrl: '{{ $user->profile_image_url }}',
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.newImage = URL.createObjectURL(file);
                }
            }
        }">
            <label class="block text-sm font-medium text-gray-700 text-right mb-1">الصورة الشخصية</label>
            <div class="mt-1 flex flex-row-reverse items-center justify-start gap-4">

                 {{-- Display current or new image preview --}}
                 {{-- We bind the src to our Alpine data --}}
                 <img class="h-16 w-16 rounded-full object-cover flex-shrink-0"
                      :src="newImage || imageUrl"
                      alt="{{ $user->first_name }} {{ $user->last_name }}">

                <div class="flex items-center space-x-4 space-x-reverse">
                    {{-- The label now triggers the hidden file input --}}
                    <label for="profile_image" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                        <span>تغيير الصورة</span>
                    </label>
                     {{-- Hidden file input with Alpine event listener --}}
                    <input id="profile_image" name="profile_image" type="file" class="sr-only" x-on:change="handleFileChange">

                    {{-- Optional: A button to clear the selection (advanced) --}}
                     <button type="button" x-show="newImage" @click="newImage = null; $refs.profileImageInput.value = ''"
                             class="text-xs text-gray-500 hover:text-gray-700">إلغاء التحديد</button>
                </div>
            </div>
             @error('profile_image', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>


        {{-- Your custom fields (first_name, last_name, phone, gov, dob, notes) go here --}}
        {{-- Make sure they are present and correctly use old() and $user->field for values --}}
         <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأول <span class="text-red-600">*</span></label>
            <input id="first_name" name="first_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-right" value="{{ old('first_name', $user->first_name) }}" required autofocus>
            @error('first_name', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>
         <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 text-right mb-1">الاسم الأخير <span class="text-red-600">*</span></label>
            <input id="last_name" name="last_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-right" value="{{ old('last_name', $user->last_name) }}" required>
             @error('last_name', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>
         {{-- Example for Email (from Breeze) --}}
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" /> {{-- Translate label --}}
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" dir="ltr"/> {{-- Add dir="ltr" --}}
             @error('email', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('عنوان بريدك الإلكتروني غير متحقق.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('انقر هنا لإعادة إرسال بريد التحقق.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('تم إرسال رابط تحقق جديد إلى عنوان بريدك الإلكتروني.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

         {{-- Add other custom fields similarly --}}
         {{-- Phone Number --}}
        <div>
            <label for="phone_number" class="block text-sm font-medium text-gray-700 text-right mb-1">رقم الهاتف (اختياري)</label>
            <input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm ltr:text-left" value="{{ old('phone_number', $user->phone_number) }}" dir="ltr">
            @error('phone_number', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>

         {{-- Governorate --}}
         {{-- Requires passing $governorates from controller if editing here --}}
        {{--
        <div>
            <label for="governorate_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المحافظة (اختياري)</label>
            <select id="governorate_id" name="governorate_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-right">
                 <option value="">-- اختر محافظة --</option>
                 @foreach($governorates ?? [] as $gov)
                    <option value="{{ $gov->governorate_id }}" {{ old('governorate_id', $user->governorate_id) == $gov->governorate_id ? 'selected' : '' }}>{{ $gov->name }}</option>
                 @endforeach
            </select>
            @error('governorate_id', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>
        --}}

         {{-- Date of Birth --}}
        <div>
            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 text-right mb-1">تاريخ الميلاد (اختياري)</label>
            <input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-right" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" style="color-scheme: light;">
             @error('date_of_birth', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
        </div>

         {{-- Notes --}}
        <div>
             <label for="notes" class="block text-sm font-medium text-gray-700 text-right mb-1">ملاحظات (اختياري)</label>
             <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-right">{{ old('notes', $user->notes) }}</textarea>
             @error('notes', 'updateProfileInformation') <p class="mt-2 text-sm text-red-600 text-right">{{ $message }}</p> @enderror
         </div>


        <div class="flex items-center gap-4 justify-end"> {{-- justify-end for RTL --}}
            <x-primary-button>{{ __('حفظ') }}</x-primary-button> {{-- Translate button text --}}

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('تم الحفظ.') }}</p> {{-- Translate message --}}
            @endif
        </div>
    </form>
</section>