{{-- Sidebar for Admin Panel - Light Mode & RTL Optimized --}}
<aside class="admin-sidebar w-full h-full bg-white text-gray-700 flex flex-col rounded-lg shadow-md p-4 border border-gray-200">
    {{-- Logo/Brand --}}
    <div class="mb-6 border-b border-gray-200 pb-4">
        {{-- Adjusted alignment and spacing for RTL --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-end space-x-2 space-x-reverse px-2">
            {{-- Text part first for RTL --}}
             <div class="text-right">
                <span class="text-xl font-bold text-gray-800">منصة الأخبار الرسمية</span>
                <span class="text-xs font-normal block text-gray-500">لوحة التحكم</span>
            </div>
            {{-- Logo SVG --}}
            <svg class="h-8 w-auto text-indigo-600" fill="currentColor" viewBox="0 0 20 20"> {{-- Logo on the left in RTL --}}
                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01-.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-grow space-y-1">
        @php
        function adminNavLinkRTL($routeName, $iconSvgPath, $label) {
            // Consistent Light Mode Colors
            $activeClasses = request()->routeIs($routeName.'*')
                ? 'bg-indigo-100 text-indigo-800 font-semibold' // Brighter active state
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
            $url = Route::has($routeName) ? route($routeName) : '#';

            $iconColorClass = request()->routeIs($routeName.'*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500';

            // Using ms-3 (margin-start) for RTL icon spacing
            return <<<HTML
            <a href="{$url}"
               class="group flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition duration-150 ease-in-out {$activeClasses}">
                <svg class="w-5 h-5 ms-3 {$iconColorClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> {{-- Icon on the LEFT for RTL --}}
                    {$iconSvgPath}
                </svg>
                <span class="flex-grow text-right">{$label}</span> 
            </a>
            HTML;
        }
        @endphp

        {{-- Links using the RTL helper function --}}
        {!! adminNavLinkRTL('admin.dashboard', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>', 'لوحة التحكم') !!}
        {!! adminNavLinkRTL('admin.users.index', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>', 'إدارة المستخدمين') !!}
        {!! adminNavLinkRTL('admin.governorates.index', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>', 'إدارة المحافظات') !!}
        {!! adminNavLinkRTL('admin.regions.index', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>', 'إدارة المناطق') !!}
        {!! adminNavLinkRTL('admin.siteinfo.edit', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>', 'إعدادات الموقع') !!}

    </nav>

    {{-- Footer --}}
     <div class="mt-auto pt-4 border-t border-gray-200 text-center text-xs text-gray-400">
        © {{ date('Y') }} {{ 'منصة الأخبار الرسمية' }}
    </div>
</aside>