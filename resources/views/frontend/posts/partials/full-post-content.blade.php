@props(['post', 'isComparison' => false])

<div class="px-4 lg:px-0">
    {{-- Status and Correction Alerts --}}
    @if(!$isComparison)
        @if($post->post_status === 'fake' && $post->correction)
            <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-400 text-red-800 rounded-lg">
                <p class="text-sm font-medium">هذا الخبر تم التحقق منه وتبيّن أنه <span class="font-bold">مزيف</span>. الحقيقة في المنشور التالي:
                    <a href="{{ route('frontend.posts.show', $post->correction) }}" class="font-bold text-red-700 hover:text-red-600">
                       {{ $post->correction->title }} →
                    </a>
                </p>
            </div>
        @endif
         @if($post->post_status === 'real' && $post->correctedPosts->isNotEmpty())
            <div class="mb-6 p-4 bg-green-50 border-r-4 border-green-400 text-green-800 rounded-lg">
                <p class="text-sm font-medium">هذا المنشور الرسمي يصحح الادعاءات في المنشورات التالية:
                    @foreach($post->correctedPosts as $fakePost)
                        <a href="{{ route('frontend.posts.show', $fakePost) }}" class="font-bold hover:underline">#{{ $fakePost->post_id }}</a>{{ !$loop->last ? '،' : '' }}
                    @endforeach
                </p>
            </div>
         @endif
    @endif


    {{-- Category/Location and Favorite Button --}}
    <div class="flex justify-between items-center">
        <div class="text-base font-semibold leading-7 text-indigo-600 text-right">{{ $post->region->governorate->name ?? 'أخبار عامة' }}</div>
        @if(!$isComparison)
            @auth
                @php $isFavorited = $post->favorites->contains('user_id', Auth::id()); @endphp
                <button title="{{ $isFavorited ? 'إزالة من المفضلة' : 'إضافة للمفضلة' }}"
                        data-post-id="{{ $post->post_id }}"
                        data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
                        class="favorite-button text-gray-400 hover:text-rose-600 transition-colors duration-200 flex-shrink-0 {{ $isFavorited ? 'text-rose-600' : '' }}">
                    <svg class="h-8 w-8 favorite-icon" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </button>
            @endauth
        @endif
    </div>
     {{-- Title --}}
    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl text-right">{{ $post->title }}</h1>
    {{-- Meta --}}
    <div class="mt-4 text-sm text-gray-500 text-right">
         بواسطة {{ $post->user->first_name ?? 'محرر' }} | نُشر في {{ $post->created_at->isoFormat('D MMMM YYYY') }}
    </div>

     {{-- Main Image --}}
     @if($post->images->isNotEmpty())
        <figure class="mt-8">
            <img class="w-full max-h-[500px] rounded-xl bg-gray-50 object-cover"
                 src="{{ asset('storage/' . ($post->images->first()->sizes['medium'] ?? $post->images->first()->image_url)) }}"
                 alt="{{ $post->images->first()->caption ?? $post->title }}">
        </figure>
    @endif

    {{-- Main Content --}}
    <article class="mt-8 prose prose-lg max-w-none text-right leading-relaxed text-gray-700" dir="auto">
        {!! nl2br(e($post->text_content)) !!}
    </article>

     {{-- Attached Media Gallery --}}
     @if($post->images->count() > 1 || $post->videos->isNotEmpty())
     <div class="mt-10 pt-6 border-t border-gray-200">
         <h3 class="text-xl font-semibold text-gray-800 mb-4 text-right">الوسائط المرفقة</h3>
         @if($post->images->count() > 1)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                 @foreach($post->images as $image)
                     <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery-{{$post->post_id}}" data-caption="{{ $image->caption ?? $post->title }}"
                       class="block aspect-w-1 aspect-h-1 rounded-lg overflow-hidden border hover:opacity-80 transition">
                        <img src="{{ asset('storage/' . ($image->sizes['thumbnail'] ?? $image->image_url)) }}" alt="{{ $image->caption ?? 'صورة مرفقة' }}" class="w-full h-full object-cover">
                    </a>
                @endforeach
            </div>
         @endif
          @if($post->videos->isNotEmpty())
            <div class="mt-8">
                @foreach($post->videos as $video)
                    <video controls preload="metadata" class="w-full rounded-lg shadow-md mt-4"><source src="{{ asset('storage/' . $video->video_url) }}" type="video/mp4"></video>
                @endforeach
            </div>
        @endif
     </div>
     @endif
</div>
