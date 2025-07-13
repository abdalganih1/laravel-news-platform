<div class="px-4 lg:px-0">
    {{-- Status and Correction Alerts (remain the same) --}}
    @if($post->post_status === 'fake' && $post->correction)
        <div class="mb-6 p-4 bg-red-50 border-r-4 border-red-400 text-red-800 rounded-lg">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.332-.216 3.001-1.742 3.001H4.42c-1.526 0-2.492-1.669-1.742-3.001l5.58-9.92zM10 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium">هذا الخبر تم التحقق منه وتبيّن أنه <span class="font-bold">مزيف</span>. الحقيقة في المنشور التالي:</p>
                    <div class="mt-2 text-sm">
                        <a href="{{ route('frontend.posts.show', $post->correction) }}" class="font-bold text-red-700 hover:text-red-600">
                           {{ $post->correction->title }} →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
     @if($post->post_status === 'real' && $post->correctedPosts->isNotEmpty()) {{-- Check if this real post corrects others --}}
        <div class="mb-6 p-4 bg-green-50 border-r-4 border-green-400 text-green-800 rounded-lg">
             <div class="flex items-start gap-3">
                 <div class="flex-shrink-0">
                     <svg class="h-6 w-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                 </div>
                <p class="text-sm font-medium">هذا المنشور الرسمي يصحح الادعاءات في المنشورات التالية:
                    @foreach($post->correctedPosts as $fakePost)
                        <a href="{{ route('frontend.posts.show', $fakePost) }}" class="font-bold hover:underline">#{{ $fakePost->post_id }}</a>{{ !$loop->last ? '،' : '' }}
                    @endforeach
                </p>
             </div>
        </div>
     @endif


    {{-- Category/Location and Favorite Button --}}
    <div class="flex justify-between items-center">
        <div class="text-base font-semibold leading-7 text-indigo-600 text-right">{{ $post->region->governorate->name ?? 'أخبار عامة' }}</div>
         {{-- Favorite Button (Forms - Only for authenticated users) --}}
         @auth
             @php
                // Check if the current user has favorited this post
                $isFavorited = $post->favorites->contains('user_id', Auth::id());
             @endphp

             @if($isFavorited)
                 {{-- Form to UNFAVORITE --}}
                 <form action="{{ route('frontend.favorites.destroy', $post) }}" method="POST" class="inline-block">
                     @csrf
                     @method('DELETE')
                     <button type="submit" title="إزالة من المفضلة" class="text-rose-600 hover:text-rose-800 transition-colors duration-200 flex-shrink-0">
                         <svg class="h-8 w-8 favorite-icon" fill="currentColor" stroke="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                     </button>
                 </form>
             @else
                 {{-- Form to FAVORITE --}}
                  <form action="{{ route('frontend.favorites.store', $post) }}" method="POST" class="inline-block">
                     @csrf
                     <button type="submit" title="إضافة للمفضلة" class="text-gray-400 hover:text-rose-600 transition-colors duration-200 flex-shrink-0">
                         <svg class="h-8 w-8 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                     </button>
                 </form>
             @endif
         @endauth
    </div>
     {{-- Title --}}
    <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl text-right">{{ $post->title }}</h1>
    {{-- Meta --}}
    <div class="mt-4 text-sm text-gray-500 text-right">
         بواسطة {{ $post->user->first_name ?? 'محرر' }} | نُشر في {{ $post->created_at->isoFormat('D MMMM YYYY') }}
         @if($post->region)
            | المنطقة: {{ $post->region->name }} ({{ $post->region->governorate->name ?? '' }})
         @endif
    </div>

     {{-- Main Image --}}
     @if($post->images->isNotEmpty())
        <figure class="mt-8">
            <img class="w-full max-h-[500px] rounded-xl bg-gray-50 object-cover"
                 src="{{ asset('storage/' . $post->images->first()->sizes['medium']) }}"
                 alt="{{ $post->images->first()->caption ?? $post->title }}">
            @if($post->images->first()->caption)
            <figcaption class="mt-4 flex gap-x-2 text-sm leading-6 text-gray-500 text-right">
                <svg class="mt-0.5 h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>{{ $post->images->first()->caption }}</span>
            </figcaption>
            @endif
        </figure>
    @endif

    {{-- Main Content --}}
    <article class="mt-8 prose prose-lg max-w-none text-right leading-relaxed text-gray-700" dir="auto">
        {!! nl2br(e($post->text_content)) !!}
    </article>

    <!-- Verification Result Section -->
    @if($post->post_status === 'real' || $post->post_status === 'fake')
    <div class="mt-10 pt-6 border-t border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-right">النتيجة والتحقق</h3>
        @if($post->post_status === 'real')
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <p class="font-bold text-green-800">خبر مؤكد</p>
                @if($post->claim && $post->claim->admin_notes)
                <p class="text-green-700 mt-1">{{ $post->claim->admin_notes }}</p>
                @else
                <p class="text-green-700 mt-1">تم التحقق من صحة هذا الخبر من قبل فريقنا.</p>
                @endif
            </div>
        @elseif($post->post_status === 'fake')
            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                <p class="font-bold text-red-800">خبر زائف</p>
                @if($post->claim && $post->claim->admin_notes)
                <p class="text-red-700 mt-1">{{ $post->claim->admin_notes }}</p>
                @else
                <p class="text-red-700 mt-1">تم التحقق وتبيّن أن هذا الخبر زائف.</p>
                @endif
            </div>
        @endif
    </div>
    @elseif($post->post_status === 'pending_verification')
    <div class="mt-10 pt-6 border-t border-gray-200">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 text-right">النتيجة والتحقق</h3>
        <div class="p-4 rounded-lg bg-yellow-50 border border-yellow-200">
            <p class="font-bold text-yellow-800">قيد التحقق</p>
            <p class="text-yellow-700 mt-1">يجري حاليًا التحقق من صحة هذا الخبر.</p>
        </div>
    </div>
    @endif


     {{-- Attached Media Gallery --}}
     <div class="mt-10 pt-6 border-t border-gray-200">
         @if($post->images->count() > 0)
            <h3 class="text-xl font-semibold text-gray-800 mb-4 text-right">معرض الوسائط</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                 @foreach($post->images as $image)
                     <a href="{{ asset('storage/' . $image->original) }}" data-fancybox="gallery" data-caption="{{ $image->caption ?? $post->title }}"
                       class="block aspect-w-1 aspect-h-1 rounded-lg overflow-hidden border hover:opacity-80 transition">
                        <img src="{{ asset('storage/' . $image->sizes['thumbnail']) }}" alt="{{ $image->caption ?? 'صورة مرفقة' }}" class="w-full h-full object-cover">
                    </a>
                @endforeach
            </div>
         @endif
          @if($post->videos->isNotEmpty())
            <div class="mt-8">
                @foreach($post->videos as $video)
                    <video controls preload="metadata" class="w-full rounded-lg shadow-md mt-4"><source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4"></video>
                @endforeach
            </div>
        @endif
     </div>
</div>