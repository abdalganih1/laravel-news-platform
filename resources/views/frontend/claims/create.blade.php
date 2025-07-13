<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('الإبلاغ عن خبر مشكوك به') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <div class="text-right mb-6">
                        <h3 class="text-lg font-medium text-gray-900">نموذج الإبلاغ</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            شكرًا لمساهمتك في مكافحة الأخبار المضللة. يرجى تقديم أكبر قدر ممكن من التفاصيل.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-md relative" role="alert">
                            <ul class="list-disc list-inside text-sm text-right">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('frontend.claims.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 text-right mb-1">عنوان البلاغ (موجز للادعاء) <span class="text-red-600">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right">
                        </div>

                         {{-- External URL --}}
                        <div>
                            <label for="external_url" class="block text-sm font-medium text-gray-700 text-right mb-1">رابط الخبر المشكوك به (إن وجد)</label>
                            <input type="url" name="external_url" id="external_url" value="{{ old('external_url') }}" placeholder="https://facebook.com/page/post..."
                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm ltr:text-left" dir="ltr">
                        </div>

                         {{-- Reported Text --}}
                        <div>
                            <label for="reported_text" class="block text-sm font-medium text-gray-700 text-right mb-1">نص الخبر (إذا لم يكن هناك رابط)</label>
                            <textarea name="reported_text" id="reported_text" rows="5"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right">{{ old('reported_text') }}</textarea>
                        </div>

                        {{-- User Notes --}}
                        <div>
                            <label for="user_notes" class="block text-sm font-medium text-gray-700 text-right mb-1">لماذا تعتقد أن هذا الخبر مزيف؟ (اختياري)</label>
                            <textarea name="user_notes" id="user_notes" rows="3"
                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right">{{ old('user_notes') }}</textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div>
                            <label for="images" class="block text-sm font-medium text-gray-700 text-right mb-1">إرفاق صور داعمة (مثل لقطات شاشة)</label>
                            <input type="file" name="images[]" id="images" multiple accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 cursor-pointer">
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    إرسال البلاغ
                                </button>
                                <a href="{{ url()->previous() }}"
                                   class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>