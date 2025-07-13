<x-app-layout>
    {{-- Hero Section --}}
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">منصة الأخبار الرسمية</span>
                <span class="block text-indigo-600">لمكافحة الأخبار المزيفة</span>
            </h1>
            <p class="mt-4 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-6 md:text-xl md:max-w-3xl">
                مصدرك الموثوق للأخبار الدقيقة والمعتمدة في سوريا. نتحقق من صحة الأخبار ونكشف الادعاءات المضللة لتعزيز الوعي المجتمعي.
            </p>
            <div class="mt-6 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                <div class="rounded-md shadow">
                    <a href="{{ route('frontend.posts.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                        تصفح الأخبار
                    </a>
                </div>
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ms-3">
                    <a href="{{ route('frontend.claims.create') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                        الإبلاغ عن خبر
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest Posts Section --}}
    <div class="bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="relative max-w-7xl mx-auto">
            <div class="text-right">
                <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">أحدث الأخبار الموثوقة</h2>
                {{-- ... --}}
            </div>
            {{-- This grid layout is still perfect for the new card design --}}
            <div class="mt-12 mx-auto grid gap-8 lg:grid-cols-1 lg:max-w-none">
                @forelse ($latestPosts as $post)
                    @include('frontend.posts.partials.post-card', ['post' => $post])
                @empty
                    <p class="lg:col-span-2 text-center ...">لا توجد أخبار...</p>
                @endforelse
            </div>
        </div>
    </div>

     {{-- Recently Debunked Section (remains the same as it's a single column list) --}}
     @if($recentlyDebunked->isNotEmpty())
    <div class="bg-white pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="relative max-w-7xl mx-auto">
             <div class="text-right">
                <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">إشاعات تم تكذيبها مؤخرًا</h2>
                <p class="mt-3 sm:mt-4 max-w-2xl text-xl text-gray-500">
                    نظرة على الادعاءات المضللة التي تم التحقق منها وتصحيحها.
                </p>
            </div>
             <div class="mt-12 space-y-12">
                @foreach($recentlyDebunked as $post)
                    @include('frontend.posts.partials.debunked-post-card', ['post' => $post])
                @endforeach
            </div>
        </div>
    </div>
    @endif
</x-app-layout>