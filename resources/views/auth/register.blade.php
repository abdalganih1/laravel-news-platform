<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- First Name --}}
        <div>
            <x-input-label for="first_name" :value="__('الاسم الأول')" /> {{-- Translate label --}}
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        {{-- Last Name --}}
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('الاسم الأخير')" /> {{-- Translate label --}}
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" /> {{-- Translate label --}}
            {{-- Add dir="ltr" for email --}}
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" dir="ltr"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Phone Number (Optional - if added to Request rules) --}}
         <div class="mt-4">
            <x-input-label for="phone_number" :value="__('رقم الهاتف (اختياري)')" />
            {{-- Add dir="ltr" for phone number --}}
            <x-text-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone_number')" autocomplete="tel" dir="ltr"/>
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        {{-- Governorate (Optional - if added to Request rules and passed from controller) --}}
        {{-- You need to pass $governorates to this view from the create method in RegisteredUserController --}}
        @if(!empty($governorates)) {{-- Show only if governorates are passed --}}
        <div class="mt-4">
             <x-input-label for="governorate_id" :value="__('المحافظة (اختياري)')" />
             <select id="governorate_id" name="governorate_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                 <option value="">-- اختر محافظة --</option>
                 @foreach ($governorates as $governorate)
                    <option value="{{ $governorate->governorate_id }}" {{ old('governorate_id') == $governorate->governorate_id ? 'selected' : '' }}>
                        {{ $governorate->name }}
                    </option>
                 @endforeach
             </select>
            <x-input-error :messages="$errors->get('governorate_id')" class="mt-2" />
         </div>
        @endif


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" /> {{-- Translate label --}}

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" dir="ltr"/> {{-- Add dir="ltr" --}}

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" /> {{-- Translate label --}}

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" dir="ltr"/> {{-- Add dir="ltr" --}}

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            {{-- Link to Login page --}}
             <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('هل لديك حساب بالفعل؟') }} {{-- Translate link text --}}
             </a>

            <x-primary-button class="ms-4"> {{-- ms-4 for RTL spacing --}}
                {{ __('تسجيل') }} {{-- Translate button text --}}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>