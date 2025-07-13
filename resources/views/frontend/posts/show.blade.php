<x-app-layout>
    <div class="bg-gray-50 py-16 sm:py-24"> {{-- Changed background to gray-50 for consistency --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ... (Debunking/Correction Layout remains the same) ... --}}
            @if($post->post_status === 'real' && $post->correctedPosts->isNotEmpty())
               {{-- ... (Debunking comparison layout) ... --}}

                {{-- Full content of the REAL post --}}
                <div class="mt-16 bg-white rounded-xl shadow-lg border border-gray-200 p-6 sm:p-8" id="full-article-content">
                    @include('frontend.posts.partials.full-post-content', ['post' => $post])
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
         document.addEventListener('DOMContentLoaded', function () {
             // Function to handle favoriting
             async function toggleFavorite(button) {
                 const postId = button.dataset.postId;
                 let isFavorited = button.dataset.favorited === 'true';
                 const icon = button.querySelector('.favorite-icon');

                 // Determine the URL and method
                 const url = isFavorited
                     ? "{{ url('/posts') }}/" + postId + "/unfavorite" // Use DELETE route
                     : "{{ url('/posts') }}/" + postId + "/favorite";    // Use POST route
                 const method = isFavorited ? 'DELETE' : 'POST';

                 try {
                     // Optimistically update the UI
                     button.disabled = true; // Disable button during request
                     isFavorited = !isFavorited; // Toggle state
                     button.dataset.favorited = isFavorited; // Update data attribute
                     // Toggle icon appearance
                     if (isFavorited) {
                         icon.classList.remove('text-gray-400'); // Remove outline color
                         icon.classList.add('text-rose-600');    // Add solid color
                         icon.setAttribute('fill', 'currentColor');
                         icon.setAttribute('stroke', 'none');
                     } else {
                         icon.classList.remove('text-rose-600'); // Remove solid color
                         icon.classList.add('text-gray-400');    // Add outline color
                         icon.setAttribute('fill', 'none');
                         icon.setAttribute('stroke', 'currentColor');
                     }


                     const response = await fetch(url, {
                         method: method,
                         headers: {
                             'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                             'Content-Type': 'application/json',
                             'Accept': 'application/json',
                         },
                         // For DELETE, the body is usually not needed
                         // For POST, if you had extra data, you'd add: body: JSON.stringify({ ... })
                     });

                     const data = await response.json();

                     if (!response.ok) {
                          // Revert UI on error
                          isFavorited = !isFavorited; // Revert state
                          button.dataset.favorited = isFavorited; // Revert data attribute
                          // Revert icon appearance
                          if (isFavorited) { // Reverted back to favorited
                              icon.classList.remove('text-gray-400');
                              icon.classList.add('text-rose-600');
                              icon.setAttribute('fill', 'currentColor');
                              icon.setAttribute('stroke', 'none');
                          } else { // Reverted back to unfavorited
                              icon.classList.remove('text-rose-600');
                              icon.classList.add('text-gray-400');
                              icon.setAttribute('fill', 'none');
                              icon.setAttribute('stroke', 'currentColor');
                          }
                         console.error('Error toggling favorite status:', data.message);
                         alert('حدث خطأ: ' + data.message); // Show user an alert
                     }

                     // Re-enable button
                     button.disabled = false;

                     // Optional: Show a small success message near the button
                     // console.log(data.message);

                 } catch (error) {
                     console.error('Network error:', error);
                     alert('حدث خطأ في الاتصال.'); // Show user an alert

                      // Revert UI on network error (same as response error)
                      isFavorited = !isFavorited;
                      button.dataset.favorited = isFavorited;
                      if (isFavorited) {
                          icon.classList.remove('text-gray-400');
                          icon.classList.add('text-rose-600');
                          icon.setAttribute('fill', 'currentColor');
                          icon.setAttribute('stroke', 'none');
                      } else {
                          icon.classList.remove('text-rose-600');
                          icon.classList.add('text-gray-400');
                          icon.setAttribute('fill', 'none');
                          icon.setAttribute('stroke', 'currentColor');
                      }

                      button.disabled = false;
                 }
             }

             // Attach event listeners to all favorite buttons
             document.querySelectorAll('.favorite-button').forEach(button => {
                 button.addEventListener('click', function() {
                     toggleFavorite(this);
                 });
             });
         });
     </script>
     @endpush
     @endauth {{-- End auth check for JS --}}

    {{-- Fancybox scripts (optional but recommended for image viewing) --}}
    {{-- ... (Fancybox code remains the same) ... --}}

</x-app-layout>