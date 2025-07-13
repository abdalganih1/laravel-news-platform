<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إدارة معلومات الموقع') }}
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
                         {{-- Note the method spoofing for PATCH --}}
                        <form action="{{ route('admin.siteinfo.update') }}" method="POST">
                            @csrf
                            @method('PATCH') {{-- Use PATCH as we are updating a single known resource --}}

                            <div class="p-6 space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                    تعديل معلومات الموقع العامة والاتصال
                                </h3>

                                {{-- Flash Message for Success --}}
                                @include('partials.flash-messages')

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

                                {{-- Site Title (Optional to edit, usually static) --}}
                                {{--
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 text-right mb-1">عنوان المحتوى (مثال: حولنا)</label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $siteInfo->title) }}"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                    @error('title') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>
                                --}}

                                {{-- Main Content (e.g., About Us text) --}}
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 text-right mb-1">المحتوى الرئيسي (مثل نص "حولنا")</label>
                                    {{-- Consider using a Rich Text Editor (like Trix, CKEditor, TinyMCE) for this field --}}
                                    <textarea name="content" id="content" rows="10"
                                              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('content', $siteInfo->content) }}</textarea>
                                    @error('content') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                {{-- Contact Phone --}}
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 text-right mb-1">رقم هاتف الاتصال</label>
                                    <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $siteInfo->contact_phone) }}"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                    @error('contact_phone') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                {{-- Contact Email --}}
                                <div>
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 text-right mb-1">بريد الاتصال الإلكتروني</label>
                                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $siteInfo->contact_email) }}"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                    @error('contact_email') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                {{-- Website URL --}}
                                <div>
                                    <label for="website_url" class="block text-sm font-medium text-gray-700 text-right mb-1">رابط الموقع الإلكتروني الرسمي</label>
                                    <input type="url" name="website_url" id="website_url" value="{{ old('website_url', $siteInfo->website_url) }}" placeholder="https://example.com"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 ltr:text-left sm:text-sm" dir="ltr">
                                     @error('website_url') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                            </div> {{-- End Main Padding --}}

                            {{-- Form Footer with Submit Button --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-start"> {{-- Removed space-x --}}
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    حفظ التغييرات
                                </button>
                                {{-- No cancel button needed as it redirects back here --}}
                            </div>
                        </form>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>