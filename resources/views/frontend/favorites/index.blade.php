<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('المنشورات المفضلة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    @include('partials.flash-messages')

                    @if($favorites->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318A4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد منشورات في المفضلة</h3>
                            <p class="mt-1 text-sm text-gray-500">يمكنك إضافة المنشورات التي تعجبك إلى قائمة المفضلة من صفحة عرض المنشور.</p>
                            <div class="mt-6">
                              <a href="{{ route('frontend.posts.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                تصفح الأخبار لإضافة للمفضلة
                              </a>
                            </div>
                        </div>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4 text-right border-b border-gray-200 pb-3">قائمة المفضلة الخاصة بك ({{ $favorites->total() }})</h3>
                        <ul role="list" class="divide-y divide-gray-200">
                             @foreach($favorites as $favorite)
                                <li>
                                    <div class="block hover:bg-gray-50 px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate text-right max-w-xs">
                                                <a href="{{ route('frontend.posts.show', $favorite->post) }}" class="hover:underline">
                                                    {{ $favorite->post->title ?? 'منشور محذوف' }}
                                                </a>
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex space-x-4 space-x-reverse">
                                                <p class="text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" /></svg>
                                                     تم الإضافة في: {{ $favorite->created_at->isoFormat('D MMMM YYYY') }}
                                                </p>
                                                 {{-- Remove from Favorites Button --}}
                                                 <form action="{{ route('frontend.favorites.destroy', $favorite->post) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إزالة هذا المنشور من المفضلة؟');">
                                                     @csrf
                                                     @method('DELETE')
                                                     <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">إزالة</button>
                                                 </form>
                                            </div>
                                        </div>
                                         {{-- Optional: Display summary/image for each favorite item --}}
                                    </div>
                                </li>
                             @endforeach
                        </ul>
                         <div class="mt-6">
                            {{ $favorites->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>