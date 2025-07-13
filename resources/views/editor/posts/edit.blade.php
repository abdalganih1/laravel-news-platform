<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل المنشور:') }} {{ Str::limit($post->title, 40) }}
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
                        <form action="{{ route('editor.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH') {{-- Use PATCH for update --}}

                            <div class="p-6 space-y-6">
                                <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                    تعديل تفاصيل المنشور
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
                                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required maxlength="255"
                                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                    @error('title') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                 {{-- Text Content --}}
                                <div>
                                    <label for="text_content" class="block text-sm font-medium text-gray-700 text-right mb-1">المحتوى النصي <span class="text-red-600">*</span></label>
                                    <textarea name="text_content" id="text_content" rows="10" required
                                              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">{{ old('text_content', $post->text_content) }}</textarea>
                                    @error('text_content') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Region --}}
                                    <div>
                                        <label for="region_id" class="block text-sm font-medium text-gray-700 text-right mb-1">المنطقة (اختياري)</label>
                                        <select name="region_id" id="region_id"
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- اختر منطقة --</option>
                                             @foreach ($groupedRegions as $governorateName => $regionsInGroup)
                                                <optgroup label="{{ $governorateName }}">
                                                    @foreach ($regionsInGroup as $region)
                                                        <option value="{{ $region->region_id }}" {{ old('region_id', $post->region_id) == $region->region_id ? 'selected' : '' }}>
                                                            {{ $region->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('region_id') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>

                                     {{-- Post Status --}}
                                    <div>
                                        <label for="post_status" class="block text-sm font-medium text-gray-700 text-right mb-1">حالة المنشور <span class="text-red-600">*</span></label>
                                        <select name="post_status" id="post_status" required
                                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            {{-- Use $statuses passed from controller --}}
                                            @foreach($statuses as $status)
                                                 <option value="{{ $status }}" {{ old('post_status', $post->post_status) == $status ? 'selected' : '' }}>
                                                     @if($status === 'real') حقيقي
                                                     @elseif($status === 'fake') مزيف
                                                     @elseif($status === 'pending_verification') قيد التحقق
                                                     @endif
                                                 </option>
                                            @endforeach
                                        </select>
                                         @error('post_status') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- Corrected Post ID (Conditional) --}}
                                <div x-data="{ isFake: {{ old('post_status', $post->post_status) == 'fake' ? 'true' : 'false' }} }"
                                     x-init="$watch('isFake', value => $el.style.display = value ? 'block' : 'none')"
                                     :style="{ display: isFake ? 'block' : 'none' }" >
                                    <label for="corrected_post_id" class="block text-sm font-medium text-gray-700 text-right mb-1">ربط بمنشور التصحيح (إذا كان مزيفًا - اختياري)</label>
                                    {{-- Consider using a searchable select (e.g., Select2, TomSelect) for better UX --}}
                                    <select name="corrected_post_id" id="corrected_post_id"
                                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right sm:text-sm">
                                            <option value="">-- لا يوجد تصحيح --</option>
                                            @foreach($realPosts as $realPost)
                                                <option value="{{ $realPost->post_id }}" {{ old('corrected_post_id', $post->corrected_post_id) == $realPost->post_id ? 'selected' : '' }}>
                                                   #{{ $realPost->post_id }} - {{ Str::limit($realPost->title, 50) }}
                                                </option>
                                            @endforeach
                                    </select>
                                    @error('corrected_post_id') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>


                                {{-- Manage Existing Images --}}
                                @if($post->images->isNotEmpty())
                                <div class="pt-4 border-t border-gray-100">
                                     <label class="block text-sm font-medium text-gray-700 text-right mb-2">الصور الحالية (حدد للحذف)</label>
                                     <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                        @foreach($post->images as $image)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($image->image_url) }}" alt="صورة المنشور" class="w-full h-24 object-cover rounded-md">
                                                <label for="delete_image_{{ $image->image_id }}" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-md">
                                                     <input type="checkbox" name="delete_images[]" id="delete_image_{{ $image->image_id }}" value="{{ $image->image_id }}" class="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                     <span class="ml-2 text-xs text-white font-semibold">حذف</span>
                                                </label>
                                            </div>
                                        @endforeach
                                     </div>
                                      @error('delete_images.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>
                                @endif

                                {{-- Add New Images --}}
                                <div>
                                    <label for="new_images" class="block text-sm font-medium text-gray-700 text-right mb-1">إضافة صور جديدة (اختياري, حد أقصى 5)</label>
                                    <input type="file" name="new_images[]" id="new_images" multiple accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    @error('new_images') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                    @error('new_images.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>

                                {{-- Manage Existing Videos (Similar logic to images) --}}
                                @if($post->videos->isNotEmpty())
                                <div class="pt-4 border-t border-gray-100">
                                     <label class="block text-sm font-medium text-gray-700 text-right mb-2">الفيديوهات الحالية (حدد للحذف)</label>
                                     <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                        @foreach($post->videos as $video)
                                         <div class="relative group">
                                             {{-- Placeholder for video - consider adding thumbnails or player --}}
                                             <div class="w-full h-24 bg-gray-200 rounded-md flex items-center justify-center">
                                                 <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                             </div>
                                             <label for="delete_video_{{ $video->video_id }}" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-md">
                                                 <input type="checkbox" name="delete_videos[]" id="delete_video_{{ $video->video_id }}" value="{{ $video->video_id }}" class="h-5 w-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                 <span class="ml-2 text-xs text-white font-semibold">حذف</span>
                                             </label>
                                         </div>
                                        @endforeach
                                     </div>
                                      @error('delete_videos.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>
                                @endif

                                {{-- Add New Videos --}}
                                <div>
                                    <label for="new_videos" class="block text-sm font-medium text-gray-700 text-right mb-1">إضافة فيديوهات جديدة (اختياري, حد أقصى 2)</label>
                                    <input type="file" name="new_videos[]" id="new_videos" multiple accept="video/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                                    @error('new_videos') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                     @error('new_videos.*') <p class="mt-1 text-xs text-red-600 text-right">{{ $message }}</p> @enderror
                                </div>


                            </div> {{-- End Main Padding --}}

                            {{-- Form Footer with Buttons --}}
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-start space-x-4 space-x-reverse">
                                <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                     <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    تحديث المنشور
                                </button>
                                <a href="{{ route('editor.posts.index') }}"
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
    {{-- Alpine.js and script (same as create view) --}}
     @once
     @push('scripts')
         <script src="//unpkg.com/alpinejs" defer></script>
     @endpush
     @endonce
     <script>
         document.addEventListener('alpine:init', () => {
            Alpine.data('postForm', () => ({
                isFake: {{ old('post_status', $post->post_status) == 'fake' ? 'true' : 'false' }}, // Use current post status
                updateIsFake(event) {
                    this.isFake = event.target.value === 'fake';
                }
            }))
        });
         document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('post_status');
            if (statusSelect) {
                 statusSelect.addEventListener('change', (event) => {
                     Alpine.store('postForm').updateIsFake(event);
                 });
            }
        });
     </script>
</x-app-layout>