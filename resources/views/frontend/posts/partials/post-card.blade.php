{{-- Responsive Post Card V3 - Horizontal on desktop with fixed image size --}}
<article class="relative bg-white rounded-lg shadow-lg overflow-hidden transition-shadow duration-300 hover:shadow-xl">
    @if($post->post_status == 'fake')
        <span class="absolute top-2 left-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            خبر زائف
        </span>
    @elseif($post->post_status == 'real')
        <span class="absolute top-2 left-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            خبر مؤكد
        </span>
    @endif
    <div class="flex flex-col md:flex-row-reverse">

        {{-- Image Container --}}
        <div class="md:w-56 lg:w-64 flex-shrink-0">
            <a href="{{ route('frontend.posts.show', $post) }}" class="block h-full">
                <img class="h-48 w-full object-cover md:h-full md:w-full"
                     src="{{ $post->images->first() ? asset('storage/' . $post->images->first()->sizes['thumbnail']) : 'https://via.placeholder.com/400x250/f3f4f6/4b5563?text=خبر' }}"
                     alt="{{ $post->title }}">
            </a>
        </div>

        {{-- Content Container --}}
        <div class="flex-1 p-6 flex flex-col justify-between text-right" dir="rtl">
            <div class="flex-1">
                 {{-- Category/Governorate and Favorite Button (Forms) --}}
                 <div class="flex justify-between items-center">
                     <p class="text-sm font-medium text-indigo-600">
                         <a href="{{ route('frontend.posts.index', ['governorate_id' => $post->region->governorate_id ?? '']) }}" class="hover:underline">
                             {{ $post->region->governorate->name ?? 'أخبار عامة' }}
                         </a>
                     </p>
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
                                 <button type="submit" title="إزالة من المفضلة" class="text-rose-600 hover:text-rose-800 transition-colors duration-200">
                                     <svg class="h-6 w-6 favorite-icon" fill="currentColor" stroke="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                 </button>
                             </form>
                         @else
                             {{-- Form to FAVORITE --}}
                              <form action="{{ route('frontend.favorites.store', $post) }}" method="POST" class="inline-block">
                                 @csrf
                                 <button type="submit" title="إضافة للمفضلة" class="text-gray-400 hover:text-rose-600 transition-colors duration-200">
                                     <svg class="h-6 w-6 favorite-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                 </button>
                             </form>
                         @endif
                     @endauth
                 </div>
                {{-- Title and Summary --}}
                <a href="{{ route('frontend.posts.show', $post) }}" class="block mt-2">
                    <h3 class="text-lg font-semibold text-gray-900 leading-tight group-hover:text-indigo-600">
                        {{ $post->title }}
                    </h3>
                    <p class="mt-3 text-sm text-gray-500 leading-relaxed">
                        {{ Str::limit(strip_tags($post->text_content), 150) }}
                    </p>
                </a>
            </div>
            {{-- Author Meta --}}
            <div class="mt-6 flex items-center justify-end">
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">
                        بواسطة <span class="font-semibold">{{ $post->user->first_name ?? 'محرر' }}</span>
                    </p>
                    <div class="text-xs text-gray-500">
                        <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time>
                    </div>
                </div>
                 <div class="flex-shrink-0 ms-3">
                    <span class="sr-only">{{ $post->user->first_name ?? 'محرر' }}</span>
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->first_name ?? 'E') }}&color=4F46E5&background=EEF2FF" alt="">
                </div>
            </div>
        </div>
    </div>
</article>