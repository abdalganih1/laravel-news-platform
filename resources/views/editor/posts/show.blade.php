<x-app-layout>
    <x-slot name="header">
         <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right sm:text-inherit">
                {{ __('عرض المنشور:') }} <span class="font-normal">{{ Str::limit($post->title, 40) }}</span>
            </h2>
             <div class="flex items-center space-x-2 space-x-reverse self-end sm:self-auto"> {{-- Align buttons to end on small screens --}}
                 <a href="{{ route('editor.posts.edit', $post) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                     <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                     <span>تعديل</span>
                 </a>
                <a href="{{ route('editor.posts.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <span>عودة للقائمة</span>
                </a>
             </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.editor.sidebar')
                </div>
                 <div class="flex-grow space-y-6">
                     {{-- Post Content Card --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                         <div class="p-6">
                            {{-- Post Header Section --}}
                            <div class="border-b border-gray-200 pb-4 mb-6">
                                <h1 class="text-3xl font-bold text-gray-900 text-right mb-3 leading-tight">{{ $post->title }}</h1>
                                <div class="flex flex-col sm:flex-row flex-wrap justify-between items-start text-sm text-gray-500 gap-y-2">
                                    {{-- Meta Info (Author, Date) --}}
                                    <div class="text-right">
                                        <p>بواسطة: <span class="font-medium text-gray-700">{{ $post->user->first_name ?? 'غير معروف' }} {{ $post->user->last_name ?? '' }}</span></p>
                                        <p>تاريخ النشر: <span class="font-medium text-gray-700" title="{{ $post->created_at }}">{{ $post->created_at->isoFormat('dddd، D MMMM YYYY') }}</span></p>
                                        <p>آخر تحديث: <span class="font-medium text-gray-700" title="{{ $post->updated_at }}">{{ $post->updated_at->diffForHumans() }}</span></p>
                                    </div>
                                    {{-- Status and Region --}}
                                    <div class="text-right sm:text-left mt-2 sm:mt-0">
                                        <span @class([
                                            'px-3 py-1 inline-block text-xs leading-5 font-semibold rounded-full',
                                            'bg-blue-100 text-blue-800' => $post->post_status === 'pending_verification',
                                            'bg-green-100 text-green-800' => $post->post_status === 'real',
                                            'bg-red-100 text-red-800' => $post->post_status === 'fake',
                                        ])>
                                            @if($post->post_status === 'pending_verification') قيد التحقق
                                            @elseif($post->post_status === 'real') حقيقي
                                            @elseif($post->post_status === 'fake') مزيف
                                            @endif
                                        </span>
                                        @if($post->region)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <svg class="inline w-3 h-3 me-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                {{ $post->region->name }}، {{ $post->region->governorate->name ?? '' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Correction Links --}}
                                @if($post->post_status === 'fake' && $post->correction)
                                    <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 text-red-800 rounded-md text-sm">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-red-500 me-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.332-.216 3.001-1.742 3.001H4.42c-1.526 0-2.492-1.669-1.742-3.001l5.58-9.92zM10 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                تم تحديد هذا المنشور كمزيف. <br class="sm:hidden">
                                                المنشور الصحيح:
                                                <a href="{{ route('editor.posts.show', $post->correction->post_id) }}" class="font-semibold hover:underline text-red-700">
                                                    {{ Str::limit($post->correction->title, 30) }} (المنشور #{{ $post->correction->post_id }})
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($post->correctedPosts->isNotEmpty()) {{-- Use correctedPosts --}}
                                    <div class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 text-green-800 rounded-md text-sm">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 me-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                هذا المنشور يصحح المنشورات المزيفة التالية:
                                                @foreach($post->correctedPosts as $fakePost) {{-- Use correctedPosts --}}
                                                <a href="{{ route('editor.posts.show', $fakePost->post_id) }}" class="font-semibold hover:underline text-green-700 ms-1">
                                                    {{ Str::limit($fakePost->title, 25) }} (#{{ $fakePost->post_id }})
                                                </a>{{ !$loop->last ? '،' : '' }}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Post Content --}}
                            <article class="prose prose-sm sm:prose lg:prose-lg xl:prose-xl max-w-none text-right leading-relaxed text-gray-700" dir="auto">
                                {!! nl2br(e($post->text_content)) !!}
                            </article>

                             {{-- Attached Images --}}
                            @if($post->images->isNotEmpty())
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 text-right">الصور المرفقة ({{ $post->images->count() }})</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($post->images as $image)
                                            <a href="{{ Storage::url($image->image_url) }}" data-fancybox="gallery" data-caption="{{ $image->caption ?? $post->title }}"
                                               class="block relative group aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                                                <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption ?? 'صورة مرفقة' }}"
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                @if($image->caption)
                                                    <div class="absolute bottom-0 left-0 right-0 p-2 bg-black bg-opacity-50 text-white text-xs text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                        {{ Str::limit($image->caption, 30) }}
                                                    </div>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                             {{-- Attached Videos --}}
                            @if($post->videos->isNotEmpty())
                                <div class="mt-8 pt-6 border-t border-gray-200">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-4 text-right">الفيديوهات المرفقة ({{ $post->videos->count() }})</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                        @foreach($post->videos as $video)
                                         <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                             <video controls preload="metadata" class="w-full rounded-t-lg">
                                                 <source src="{{ Storage::url($video->video_url) }}" type="video/mp4"> {{-- Default type, adjust if needed --}}
                                                 متصفحك لا يدعم عرض الفيديو.
                                             </video>
                                             @if($video->caption)
                                                <p class="p-3 text-xs text-gray-600 bg-gray-50 text-right border-t border-gray-200">{{ $video->caption }}</p>
                                             @endif
                                         </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                         </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>
    {{-- Include Fancybox CSS & JS if you want to use it for image gallery --}}
    {{--
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Fancybox.bind("[data-fancybox]", {
                    // Your custom options
                });
            });
        </script>
    @endpush
    --}}
</x-app-layout>