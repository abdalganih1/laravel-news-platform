<x-app-layout>
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-right">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">أرشيف الأخبار</h1>
                <p class="mt-2 text-lg text-gray-500">تصفح جميع الأخبار الموثوقة التي تم التحقق منها ونشرها.</p>
            </div>

            {{-- Search and Filter Form --}}
            <div class="mt-8 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <form action="{{ route('frontend.posts.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4 items-end">
                    {{-- Search Input --}}
                    <div class="sm:col-span-2 md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 text-right">ابحث عن خبر</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="search" name="search" id="search" value="{{ request('search') }}"
                                   class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-10 sm:text-sm border-gray-300 rounded-md text-right" placeholder="كلمات مفتاحية، عناوين...">
                        </div>
                    </div>
                    {{-- Governorate Filter --}}
                    <div>
                        <label for="governorate_id" class="block text-sm font-medium text-gray-700 text-right">المحافظة</label>
                        <select id="governorate_id" name="governorate_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md text-right">
                            <option value="">كل المحافظات</option>
                            @foreach($governorates as $governorate)
                                <option value="{{ $governorate->governorate_id }}" {{ request('governorate_id') == $governorate->governorate_id ? 'selected' : '' }}>
                                    {{ $governorate->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Submit Button --}}
                    <div>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            بحث
                        </button>
                    </div>
                </form>
            </div>

            {{-- Posts Grid --}}
            <div class="mt-12 grid gap-16 lg:grid-cols-2 lg:gap-x-5 lg:gap-y-12">
                @forelse ($posts as $post)
                    @include('frontend.posts.partials.post-card', ['post' => $post])
                @empty
                    <div class="lg:col-span-3 bg-white p-12 rounded-lg shadow-sm text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                          <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد نتائج</h3>
                        <p class="mt-1 text-sm text-gray-500">لم نتمكن من العثور على أي أخبار تطابق معايير البحث الحالية.</p>
                      </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>