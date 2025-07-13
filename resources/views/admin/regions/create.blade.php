<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة منطقة جديدة') }}
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
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 text-right">تفاصيل المنطقة</h3>
                             @if ($errors->any())
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <strong class="font-bold">خطأ!</strong>
                                    <ul class="mt-1 list-disc list-inside text-sm text-right">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.regions.store') }}" method="POST" class="space-y-4">
                                @csrf

                                {{-- Governorate Selection --}}
                                <div>
                                    <label for="governorate_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المحافظة <span class="text-red-600">*</span></label>
                                    <select name="governorate_id" id="governorate_id" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-right">
                                        <option value="" disabled {{ old('governorate_id') ? '' : 'selected' }}>-- اختر محافظة --</option>
                                        @foreach ($governorates as $governorate)
                                            <option value="{{ $governorate->governorate_id }}" {{ old('governorate_id') == $governorate->governorate_id ? 'selected' : '' }}>
                                                {{ $governorate->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                     @error('governorate_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Region Name Input --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 text-right mb-1">اسم المنطقة <span class="text-red-600">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="150"
                                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-right">
                                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                 {{-- GPS Coordinates Input --}}
                                <div>
                                    <label for="gps_coordinates" class="block text-sm font-medium text-gray-700 text-right mb-1">إحداثيات GPS (اختياري)</label>
                                    <input type="text" name="gps_coordinates" id="gps_coordinates" value="{{ old('gps_coordinates') }}" maxlength="100" placeholder="مثال: 33.5104,36.2765"
                                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-right">
                                     @error('gps_coordinates') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Action Buttons --}}
                                <div class="pt-4 flex justify-start space-x-4 space-x-reverse">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        حفظ
                                    </button>
                                    <a href="{{ route('admin.regions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        إلغاء
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>