<div class="bg-red-50 border border-red-200 rounded-lg shadow-sm p-6 flex flex-col sm:flex-row-reverse gap-6">
    {{-- Debunked content --}}
    <div class="flex-1 text-right">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
            خبر مزيف
        </span>
        <h4 class="text-lg font-bold text-gray-800">الادعاء: "{{ $post->title }}"</h4>
        <p class="mt-2 text-sm text-gray-600">
            {{ Str::limit(strip_tags($post->text_content), 200) }}
        </p>
    </div>
    {{-- Divider --}}
    <div class="flex-shrink-0 self-center border-t sm:border-t-0 sm:border-r border-red-200 h-px sm:h-auto sm:w-px w-full"></div>
    {{-- Correction content --}}
    <div class="flex-1 text-right">
         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
            الحقيقة
        </span>
        <a href="{{ route('frontend.posts.show', $post->correction) }}" class="hover:underline">
            <h4 class="text-lg font-bold text-gray-800">{{ $post->correction->title }}</h4>
        </a>
        <p class="mt-2 text-sm text-gray-600">
             {{ Str::limit(strip_tags($post->correction->text_content), 200) }}
        </p>
         <a href="{{ route('frontend.posts.show', $post->correction) }}" class="mt-4 inline-block text-sm font-semibold text-indigo-600 hover:text-indigo-500">
             اقرأ التصحيح الكامل →
         </a>
    </div>
</div>