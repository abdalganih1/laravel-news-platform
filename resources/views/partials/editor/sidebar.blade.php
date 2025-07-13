{{-- Sidebar for Editor Panel - Light Mode & RTL Optimized --}}
<aside class="editor-sidebar w-full h-full bg-white text-gray-700 flex flex-col rounded-lg shadow-md p-4 border border-gray-200">
    {{-- Logo/Brand --}}
    <div class="mb-6 border-b border-gray-200 pb-4">
        <a href="{{ route('editor.dashboard') }}" class="flex items-center justify-end space-x-2 space-x-reverse px-2">
             <div class="text-right">
                <span class="text-xl font-bold text-gray-800">{{ config('app.name', 'Laravel') }}</span>
                <span class="text-xs font-normal block text-gray-500">لوحة تحكم المحرر</span>
            </div>
            <svg class="h-8 w-auto text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
            </svg>
        </a>
    </div>

    {{-- Navigation Links (Now using global helper functions) --}}
    <nav class="flex-grow space-y-1">
        {{-- Editor Dashboard Link --}}
        
        {!!  App\Helpers\editorNavLinkRTL('editor.dashboard', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>', 'لوحة التحكم') !!}

        {{-- Manage Posts Link --}}
        {!!  App\Helpers\editorNavLinkRTL('editor.posts.index', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>', 'إدارة المنشورات') !!}

        {{-- Review Claims Link --}}
        {!!  App\Helpers\editorNavLinkRTL('editor.claims.index', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>', 'مراجعة البلاغات') !!}
    </nav>

    {{-- Footer --}}
     <div class="mt-auto pt-4 border-t border-gray-200 text-center text-xs text-gray-400">
        © {{ date('Y') }} {{ config('app.name') }}
    </div>
</aside>