<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Welcome Message Card --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                <h3 class="text-xl font-semibold text-gray-800 text-right">أهلاً بك، <span class="text-indigo-600">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>!</h3>
                <p class="mt-1 text-sm text-gray-500 text-right">
                    نظرة عامة على نشاطك على المنصة.
                </p>
            </div>

            {{-- Request Editor Role Section (Only for normal users) --}}
            @if(Auth::user()->user_role === 'normal') {{-- Show only for normal users --}}
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <div class="text-right">
                         <h3 class="text-lg font-medium text-gray-900 mb-4">طلب الانضمام كـ محرر</h3>
                        @if(Auth::user()->isRequestingEditor()) {{-- If already requested --}}
                            <p class="text-indigo-600 font-medium mb-4">لقد قمت بتقديم طلب ليصبح محررًا وهو قيد المراجعة.</p>
                            <p class="text-sm text-gray-500">سنقوم بإعلامك بحالة الطلب فور الانتهاء من مراجعته.</p>
                        @else {{-- If not requested yet --}}
                            <p class="text-sm text-gray-600 mb-4">
                                هل ترغب بالمساهمة بشكل أكبر في مكافحة الأخبار المزيفة؟ يمكنك التقدم بطلب لتصبح محررًا ومساعدة فريقنا في التحقق من الأخبار ونشر المحتوى الرسمي.
                            </p>
                            <form action="{{ route('profile.requestEditorRole') }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    تقديم طلب
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif


            {{-- User Stats and Links --}}
             <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 text-right border-b border-gray-200 pb-3">نشاطي على المنصة</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6"> {{-- Grid for stats --}}

                        {{-- Claims Stat and Link --}}
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg shadow-sm p-5 text-right">
                             <div class="flex items-center justify-between">
                                 <div>
                                    <p class="text-sm font-medium text-indigo-600 uppercase tracking-wide">بلاغاتي</p>
                                    <p class="mt-1 text-3xl font-bold text-indigo-900">{{ $userStats['myClaimsCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-indigo-700">إجمالي عدد البلاغات التي قدمتها</p>
                                 </div>
                                  <div class="flex-shrink-0 p-3 bg-indigo-100 rounded-full">
                                     <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                  </div>
                             </div>
                             <div class="mt-4 text-right">
                                 <a href="{{ route('frontend.claims.index') }}" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                     عرض بلاغاتي →
                                 </a>
                             </div>
                        </div>

                         {{-- Favorites Stat and Link --}}
                        <div class="bg-rose-50 border border-rose-200 rounded-lg shadow-sm p-5 text-right"> {{-- Using rose color for favorites --}}
                             <div class="flex items-center justify-between">
                                 <div>
                                    <p class="text-sm font-medium text-rose-600 uppercase tracking-wide">المفضلة</p>
                                    <p class="mt-1 text-3xl font-bold text-rose-900">{{ $userStats['myFavoritesCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-rose-700">المنشورات التي قمت بحفظها</p>
                                 </div>
                                 <div class="flex-shrink-0 p-3 bg-rose-100 rounded-full">
                                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                 </div>
                             </div>
                            <div class="mt-4 text-right">
                                 <a href="{{ route('frontend.favorites.index') }}" class="inline-flex items-center text-sm font-medium text-rose-600 hover:text-rose-500">
                                     عرض المفضلة →
                                 </a>
                             </div>
                        </div>
                         {{-- Add more stats/links here if needed --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>