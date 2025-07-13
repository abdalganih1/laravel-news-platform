<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المدير') }}
        </h2>
    </x-slot>

    {{-- Main Content Area with Sidebar --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flex Container for Sidebar and Content --}}
            {{-- No need for flex-row-reverse IF the sidebar itself is correctly placed --}}
            {{-- Let's try default flex and place sidebar first in code for natural RTL flow --}}
             <div class="flex flex-col lg:flex-row gap-8">

                 {{-- Sidebar Container --}}
                <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    {{-- Include the MODIFIED LIGHT MODE & RTL sidebar partial --}}
                    @include('partials.admin.sidebar')
                </div>

                 {{-- Main Dashboard Content Area --}}
                <div class="flex-grow space-y-6">

                    {{-- Welcome Message --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
<h3 class="text-xl font-semibold text-gray-800 text-right">أهلاً بك، <span class="text-indigo-600">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>!</h3>
                        <p class="mt-1 text-sm text-gray-500 text-right">
                            نظرة عامة سريعة على إحصائيات المنصة.
                        </p>
                    </div>

                    {{-- Statistics Cards Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

                        {{-- Stat Card Structure (Inline for easier modification now) --}}
                        {{-- Card 1: Users --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between space-x-4 space-x-reverse"> {{-- space-x-reverse for RTL icon spacing --}}
                                <div class="text-right"> {{-- Text aligned right --}}
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">المستخدمين</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['userCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        (مدراء: {{ $stats['adminCount'] ?? 0 }} | محررون: {{ $stats['editorCount'] ?? 0 }})
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-full"> {{-- Icon background --}}
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.08-.991-.234-1.327M3 20h4v-2c0-.653.08-.991.234-1.327M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 0c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Posts --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">المنشورات</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['postCount'] ?? 0 }}</p>
                                     <p class="mt-1 text-xs text-gray-600 truncate" title="حقيقي: {{ $stats['realPostCount'] ?? 0 }} | مزيف: {{ $stats['fakePostCount'] ?? 0 }} | قيد التحقق: {{ $stats['pendingPostCount'] ?? 0 }}">
                                        حقيقي: {{ $stats['realPostCount'] ?? 0 }} | مزيف: {{ $stats['fakePostCount'] ?? 0 }} | ...
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-green-100 rounded-full">
                                     <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Card 3: Claims --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                             <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">البلاغات</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['claimCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        (قيد المراجعة: {{ $stats['pendingClaimCount'] ?? 0 }})
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.343 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Card 4: Locations --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                             <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">المواقع</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['governorateCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        (محافظة / {{ $stats['regionCount'] ?? 0 }} منطقة)
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-purple-100 rounded-full">
                                     <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                    </div> {{-- End Stat Cards Grid --}}

                    {{-- Other Elements Section --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 text-right"> {{-- Text align right --}}
                            <h3 class="text-lg font-medium text-gray-900 mb-4">عناصر إضافية</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                يمكنك هنا إضافة جداول للبيانات الحديثة، رسوم بيانية باستخدام مكتبة مثل Chart.js، أو روابط سريعة إضافية.
                            </p>
                            {{-- Buttons container --}}
                            <div class="mt-4 space-x-4 space-x-reverse">
                                 @if(Route::has('admin.users.create'))
                                     {{-- Button styled using standard Tailwind classes --}}
                                     <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                         {{-- Icon on the RIGHT for RTL button --}}
                                         <span>مستخدم جديد</span>
                                         <svg class="w-4 h-4 ms-2 -me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                     </a>
                                 @endif
                            </div>
                        </div>
                     </div>
{{-- Pending Editor Requests Stat Card --}}
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">طلبات محررين جديدة</p>
                                    <p class="mt-1 text-3xl font-bold text-yellow-800">{{ $stats['pendingEditorRequestsCount'] ?? 0 }}</p> {{-- We need to calculate this stat --}}
                                    <p class="mt-1 text-xs text-yellow-700">طلبات بانتظار المراجعة</p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-full">
                                     <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </div>
                            </div>
                            <div class="mt-4 text-right">
                                 {{-- Link to manage users with pending status filter --}}
                                 <a href="{{ route('admin.users.index', ['role_filter' => 'pending_editor']) }}" class="inline-flex items-center text-sm font-medium text-yellow-800 hover:text-yellow-700">
                                     مراجعة الطلبات →
                                 </a>
                             </div>
                        </div>
                    </div>

                </div> {{-- End Main Dashboard Content Area --}}
            </div> {{-- End Flex Container --}}
        </div> {{-- End Max Width Container --}}
    </div> {{-- End py-12 --}}
</x-app-layout>