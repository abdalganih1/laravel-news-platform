<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Dynamic Header Title --}}
            @if($claim)
                {{ __('إنشاء رد على البلاغ رقم:') }} <span class="font-mono">{{ $claim->claim_id }}</span>
            @else
                {{ __('إضافة منشور جديد') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.editor.sidebar')
                </div>
                 <div class="flex-grow">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <form action="{{ route('editor.posts.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                             {{-- Hidden field to pass the claim ID back to the controller --}}
                             @if($claim)
                                 <input type="hidden" name="source_claim_id" value="{{ $claim->claim_id }}">
                                 {{-- Informational banner for the editor --}}
                                 <div class="p-4 bg-cyan-50 border-b border-cyan-200 text-sm text-cyan-800 text-right">
                                    <div class="flex items-start justify-end">
                                        <div class="ms-3">
                                            أنت تقوم بإنشاء منشور للرد على البلاغ المتعلق بـ: <strong class="font-semibold">"{{ Str::limit($claim->title, 50) }}"</strong>.
                                            <br>عند الحفظ، سيتم تحديث حالة البلاغ تلقائيًا وربطه بهذا المنشور.
                                        </div>
                                         <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                         </div>
                                    </div>
                                 </div>
                             @endif

                            <div class="p-6 space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                    تفاصيل المنشور
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

                                {{-- Title --}}
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 text-right mb-1">العنوان <span class="text-red-600">*</span></label>
                                    {{-- Pre-fill title based on the claim, if it exists --}}
                                    <input type="text" name="title" id="title" value="{{ old('title', $claim ? 'التحقق من صحة خبر: ' . $claim->title : '') }}" required maxlength="255"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                    @error('title') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                 {{-- Text Content --}}
                                <div>
                                    <label for="text_content" class="block text-sm font-medium text-gray-700 text-right mb-1">المحتوى النصي <span class="text-red-600">*</span></label>
                                    {{-- Pre-fill content with claim details as a reference --}}
                                    @php
                                        $initialContent = '';
                                        if ($claim) {
                                            $initialContent = "بناءً على البلاغ الوارد حول:\n- عنوان الادعاء: \"{$claim->title}\"\n";
                                            if($claim->reported_text) $initialContent .= "- نص الادعاء: \"{$claim->reported_text}\"\n";
                                            if($claim->external_url) $initialContent .= "- الرابط المصدر: {$claim->external_url}\n";
                                            $initialContent .= "\nالنتيجة والتحقق:\n";
                                        }
                                    @endphp
                                    <textarea name="text_content" id="text_content" rows="12" required
                                              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('text_content', $initialContent) }}</textarea>
                                    @error('text_content') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                {{-- Other form fields in a grid --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                                    {{-- Region --}}
                                    <div>
                                        <label for="region_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المنطقة (اختياري)</label>
                                        <select name="region_id" id="region_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- اختر منطقة --</option>
                                            @foreach ($groupedRegions as $governorateName => $regionsInGroup)
                                                <optgroup label="{{ $governorateName }}">
                                                    @foreach ($regionsInGroup as $region)
                                                        <option value="{{ $region->region_id }}" {{ old('region_id') == $region->region_id ? 'selected' : '' }}>{{ $region->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('region_id') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                     {{-- Post Status --}}
                                    <div>
                                        <label for="post_status" class="block text-sm font-medium text-gray-700 text-right mb-1">حالة المنشور <span class="text-red-600">*</span></label>
                                        <select name="post_status" id="post_status" required x-on:change="isFake = ($event.target.value === 'fake')"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="real" {{ old('post_status', 'real') == 'real' ? 'selected' : '' }}>حقيقي (تأكيد للخبر)</option>
                                            <option value="fake" {{ old('post_status') == 'fake' ? 'selected' : '' }}>مزيف (تكذيب للخبر)</option>
                                            <option value="pending_verification" {{ old('post_status') == 'pending_verification' ? 'selected' : '' }}>قيد التحقق (للمراجعة لاحقًا)</option>
                                        </select>
                                         @error('post_status') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                 {{-- Conditional Correction Section --}}
                                <div x-data="{ isFake: '{{ old('post_status', 'real') }}' === 'fake', correctionMethod: '{{ old('correction_method', 'existing') }}' }" x-show="isFake" x-cloak class="pt-6 border-t border-gray-200 space-y-4 transition-all duration-300">
                                    <h4 class="text-md font-semibold text-gray-800 text-right">ربط التصحيح (اختياري)</h4>
                                    
                                    {{-- Correction Method Radio Buttons --}}
                                    <div class="flex justify-end space-x-4 space-x-reverse">
                                        <label class="flex items-center">
                                            <input type="radio" name="correction_method" value="existing" x-model="correctionMethod" class="form-radio h-4 w-4 text-cyan-600">
                                            <span class="mr-2 text-sm text-gray-700">ربط بمنشور حالي</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="correction_method" value="new" x-model="correctionMethod" class="form-radio h-4 w-4 text-cyan-600">
                                            <span class="mr-2 text-sm text-gray-700">إنشاء تصحيح جديد</span>
                                        </label>
                                    </div>
                                    @error('correction_method') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror

                                    {{-- 1. Link to Existing Post --}}
                                    <div x-show="correctionMethod === 'existing'" class="space-y-2">
                                        <label for="corrected_post_id" class="block text-sm font-medium text-gray-700 text-right">اختر المنشور الصحيح</label>
                                        <select name="corrected_post_id" id="corrected_post_id"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- اختر منشورًا --</option>
                                            @foreach($realPosts as $realPost)
                                                <option value="{{ $realPost->post_id }}" {{ old('corrected_post_id') == $realPost->post_id ? 'selected' : '' }}>
                                                   #{{ $realPost->post_id }} - {{ Str::limit($realPost->title, 70) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('corrected_post_id') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- 2. Create New Correction Post --}}
                                    <div x-show="correctionMethod === 'new'" class="space-y-4 p-4 bg-gray-50 rounded-lg border">
                                        <div>
                                            <label for="new_correction_title" class="block text-sm font-medium text-gray-700 text-right mb-1">عنوان المنشور الصحيح <span class="text-red-600">*</span></label>
                                            <input type="text" name="new_correction_title" id="new_correction_title" value="{{ old('new_correction_title') }}"
                                                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            @error('new_correction_title') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="new_correction_content" class="block text-sm font-medium text-gray-700 text-right mb-1">محتوى المنشور الصحيح <span class="text-red-600">*</span></label>
                                            <textarea name="new_correction_content" id="new_correction_content" rows="5"
                                                      class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('new_correction_content') }}</textarea>
                                            @error('new_correction_content') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>


                                {{-- File Uploads Section --}}
                                <div class="pt-6 border-t border-gray-200 space-y-6">
                                    {{-- Image Upload --}}
                                    <div>
                                        <label for="images" class="block text-sm font-medium text-gray-700 text-right mb-1">رفع صور (اختياري, حد أقصى 5)</label>
                                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                                               class="block w-full text-sm text-gray-500 file:me-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                        @error('images') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                        @error('images.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                    {{-- Video Upload --}}
                                    <div>
                                        <label for="videos" class="block text-sm font-medium text-gray-700 text-right mb-1">رفع فيديو (اختياري, حد أقصى 2)</label>
                                        <input type="file" name="videos[]" id="videos" multiple accept="video/*"
                                               class="block w-full text-sm text-gray-500 file:me-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer">
                                        @error('videos') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                         @error('videos.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                            </div> {{-- End Main Padding --}}

                            {{-- Form Footer with Buttons --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-start space-x-4 space-x-reverse">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    نشر المنشور
                                </button>
                                <a href="{{ $claim ? route('editor.claims.show', $claim) : route('editor.posts.index') }}"
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
    {{-- Alpine.js for conditional field --}}
    @once
    @push('scripts')
        <script src="//unpkg.com/alpinejs" defer></script>
    @endpush
    @endonce
    <script>
        // Alpine.js is self-initializing from the x-on directive in the select element.
        // No extra JS needed for this simple case.
    </script>
</x-app-layout>