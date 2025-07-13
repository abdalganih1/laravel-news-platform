<x-app-layout>
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($post->post_status == 'fake' && $post->correction)
                {{-- ### COMPARISON VIEW ### --}}
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">مقارنة بين الادعاء والتصحيح</h1>
                    <p class="mt-4 text-lg leading-8 text-gray-600">نستعرض هنا الخبر الزائف بجانب الخبر الحقيقي لتوضيح الحقيقة.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- Left Column: The FAKE Post (The Claim) --}}
                    <div class="bg-red-50 border-2 border-red-200 rounded-xl shadow-lg p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </span>
                            <h2 class="text-2xl font-bold text-red-800">الادعاء الزائف</h2>
                        </div>
                        @include('frontend.posts.partials.full-post-content', ['post' => $post, 'isComparison' => true])
                    </div>

                    {{-- Right Column: The REAL Post (The Correction) --}}
                    <div class="bg-green-50 border-2 border-green-200 rounded-xl shadow-lg p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            </span>
                            <h2 class="text-2xl font-bold text-green-800">التصحيح الحقيقي</h2>
                        </div>
                        @include('frontend.posts.partials.full-post-content', ['post' => $post->correction, 'isComparison' => true])
                    </div>

                </div>

            @else
                {{-- ### STANDARD POST LAYOUT ### --}}
                <div class="flex flex-col-reverse lg:flex-row gap-x-8 gap-y-12">
                    {{-- Sidebar with Related Posts --}}
                    @if($relatedPosts->isNotEmpty())
                    <aside class="w-full lg:w-1/3 lg:flex-shrink-0">
                        <div class="lg:sticky lg:top-24 bg-white p-6 rounded-lg border border-gray-200">
                             <h3 class="text-lg font-semibold text-gray-900 text-right mb-4">أخبار ذات صلة</h3>
                            <ul class="space-y-6">
                                 @foreach($relatedPosts as $relatedPost)
                                    <li>
                                        <a href="{{ route('frontend.posts.show', $relatedPost) }}" class="flex items-start gap-4 hover:bg-gray-50 p-2 rounded-md -m-2 transition-colors">
                                            <img class="h-16 w-16 rounded-md object-cover flex-shrink-0 bg-gray-100"
                                                 src="{{ $relatedPost->images->first() ? asset('storage/' . $relatedPost->images->first()->sizes['thumbnail']) : 'https://via.placeholder.com/150/EBF4FF/7F9CF5?text=خبر' }}"
                                                 alt="{{ $relatedPost->title }}">
                                            <div class="text-right">
                                                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $relatedPost->title }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $relatedPost->created_at->diffForHumans() }}</p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                    @endif

                    {{-- Main Post Content Container --}}
                    <div class="flex-grow {{ $relatedPosts->isEmpty() ? 'w-full' : 'lg:w-2/3' }}">
                         {{-- Include the full post content partial --}}
                         <div class="bg-white rounded-xl shadow-lg border border-gray-200">
                            @include('frontend.posts.partials.full-post-content', ['post' => $post])
                         </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ### JavaScript for Favorite Button ### --}}
     @auth {{-- Only include JS if user is authenticated --}}
     @push('scripts')
     <script>
         // Favorite button script remains the same
     </script>
     @endpush
     @endauth

</x-app-layout>
