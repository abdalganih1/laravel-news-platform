<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم المحرر') }}
        </h2>
    </x-slot>

    {{-- Main Content Area with Sidebar --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flex Container for Sidebar and Content --}}
             <div class="flex flex-col lg:flex-row gap-8">

                 {{-- Editor Sidebar Container --}}
                <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    {{-- Include the EDITOR sidebar partial --}}
                    @include('partials.editor.sidebar')
                </div>

                 {{-- Main Editor Dashboard Content Area --}}
                <div class="flex-grow space-y-8">

                    {{-- Welcome Message --}}
                     <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                        {{-- Use editor's name --}}
                        <h3 class="text-xl font-semibold text-gray-800 text-right">أهلاً بك، <span class="text-cyan-600">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>!</h3>
                        <p class="mt-1 text-sm text-gray-500 text-right">
                           هنا يمكنك إدارة المنشورات ومراجعة البلاغات المقدمة.
                        </p>
                    </div>

                    {{-- Statistics Cards Grid --}}
                    {{-- Focused on Posts and Claims --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6"> {{-- Adjusted grid to 3 columns --}}

                        {{-- Card 1: Total Posts --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">المنشورات (إجمالي)</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $editorStats['totalPostCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-gray-600 truncate" title="حقيقي: {{ $editorStats['realPostCount'] ?? 0 }} | مزيف: {{ $editorStats['fakePostCount'] ?? 0 }} | قيد التحقق: {{ $editorStats['pendingVerificationPostCount'] ?? 0 }}">
                                        حقيقي: {{ $editorStats['realPostCount'] ?? 0 }} | مزيف: {{ $editorStats['fakePostCount'] ?? 0 }} | ...
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-cyan-100 rounded-full"> {{-- Using Cyan theme --}}
                                     <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                            </div>
                        </div>

                         {{-- Card 2: Pending Claims (Important!) --}}
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-300 rounded-lg shadow-lg p-5 ring-2 ring-yellow-500 ring-opacity-50"> {{-- Highlighted --}}
                            <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide">بلاغات للمراجعة</p>
                                    <p class="mt-1 text-3xl font-bold text-yellow-900">{{ $editorStats['pendingClaimCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-yellow-800">
                                       بانتظار المراجعة والتصنيف
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-yellow-200/60 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-700 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.343 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                            </div>
                        </div>

                         {{-- Card 3: Reviewed/Total Claims --}}
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start justify-between space-x-4 space-x-reverse">
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">البلاغات (إجمالي)</p>
                                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $editorStats['totalClaimCount'] ?? 0 }}</p>
                                    <p class="mt-1 text-xs text-gray-600">
                                        (تمت المراجعة: {{ $editorStats['reviewedClaimCount'] ?? 0 }})
                                    </p>
                                </div>
                                <div class="flex-shrink-0 p-3 bg-gray-100 rounded-full">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                        </div>

                    </div> {{-- End Stat Cards Grid --}}

                    {{-- Quick Actions Section --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 text-right">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">إجراءات سريعة</h3>
                            <div class="flex flex-col sm:flex-row gap-4 justify-end"> {{-- Buttons stack on small, row on larger --}}
                                 @if(Route::has('editor.posts.create'))
                                     <a href="{{ route('editor.posts.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <span>إضافة منشور جديد</span>
                                        <svg class="w-4 h-4 ms-2 -me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                     </a>
                                 @endif
                                 @if(Route::has('editor.claims.index'))
                                      <a href="{{ route('editor.claims.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <span>مراجعة البلاغات المعلقة</span>
                                        <svg class="w-4 h-4 ms-2 -me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                     </a>
                                 @endif
                            </div>
                        </div>
                     </div>

                </div> {{-- End Main Dashboard Content Area --}}
            </div> {{-- End Flex Container --}}
        </div> {{-- End Max Width Container --}}
    </div> {{-- End py-12 --}}
</x-app-layout>