<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('مراجعة البلاغ رقم:') }} <span class="font-mono">{{ $claim->claim_id }}</span>
            </h2>
            <a href="{{ route('editor.claims.index', ['status' => $claim->claim_status]) }}"
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                 <span>العودة لقائمة البلاغات</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.editor.sidebar')
                </div>
                 <div class="flex-grow space-y-6">

                    @include('partials.flash-messages')

                    {{-- Main Claim Content Card --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="p-6">
                            {{-- Claim Header --}}
                            <div class="border-b border-gray-200 pb-4 mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 text-right">
                                    {{ $claim->title ?: 'بلاغ بدون عنوان' }}
                                </h3>
                                @if($claim->external_url)
                                    <div class="mt-2">
                                        <a href="{{ $claim->external_url }}" target="_blank" class="text-sm text-blue-600 hover:underline break-all ltr text-left block" dir="ltr" title="فتح الرابط الخارجي">
                                            {{ $claim->external_url }}
                                        </a>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-500 mt-2 text-right">
                                    بواسطة <span class="font-medium">{{ $claim->user->first_name ?? 'مستخدم محذوف' }}</span> - {{ $claim->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Reported Text --}}
                            @if($claim->reported_text)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1 text-right">النص المبلغ عنه:</h4>
                                <blockquote class="bg-gray-50 p-4 border-r-4 border-gray-300 text-gray-700 text-right whitespace-pre-wrap">
                                    {{ $claim->reported_text }}
                                </blockquote>
                            </div>
                            @endif

                            {{-- User Notes --}}
                            @if($claim->user_notes)
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-600 mb-1 text-right">ملاحظات المبلغ:</h4>
                                <p class="bg-yellow-50 p-3 rounded-md text-sm text-yellow-800 text-right whitespace-pre-wrap">{{ $claim->user_notes }}</p>
                            </div>
                            @endif

                            {{-- Attached Images --}}
                             @if($claim->images->isNotEmpty())
                                <div class="mb-4 pt-4 border-t border-gray-100">
                                    <h4 class="text-sm font-medium text-gray-600 mb-2 text-right">الصور المرفقة ({{ $claim->images->count() }}):</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($claim->images as $image)
                                            <a href="{{ Storage::url($image->image_url) }}" data-fancybox="claim-gallery"
                                               class="block border border-gray-200 rounded-md overflow-hidden hover:shadow-md transition-shadow">
                                                <img src="{{ Storage::url($image->image_url) }}" alt="صورة مرفقة" class="w-full h-28 object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Review Action/Details Card --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 text-right border-b border-gray-200 pb-3 mb-6">
                                حالة المراجعة
                            </h3>

                            @if($claim->claim_status === 'pending')
                                {{-- Actions for a PENDING claim --}}
                                <div class="text-right space-y-4">
                                    <div class="flex items-center justify-end p-4 bg-yellow-50 border-r-4 border-yellow-400 rounded-md">
                                        <div class="ms-3">
                                            <p class="text-sm font-medium text-yellow-800">
                                                هذا البلاغ بانتظار المراجعة.
                                            </p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                الخطوة التالية هي إنشاء منشور رسمي للرد على هذا الادعاء (سواء لتأكيده أو نفيه).
                                            </p>
                                        </div>
                                         <div class="flex-shrink-0">
                                             <svg class="h-6 w-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.332-.216 3.001-1.742 3.001H4.42c-1.526 0-2.492-1.669-1.742-3.001l5.58-9.92zM10 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="pt-4 flex justify-start space-x-4 space-x-reverse">
                                        {{-- The main action button --}}
                                        <a href="{{ route('editor.posts.create', $claim) }}"
                                           class="inline-flex items-center justify-center px-5 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                            إنشاء منشور رد
                                        </a>
                                        {{-- Optional: Cancel Claim Button --}}
                                        {{-- Optional: Cancel Claim Button in resources/views/editor/claims/show.blade.php --}}
                                        <form action="{{ route('editor.claims.cancel', $claim) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا البلاغ؟ سيتم إهماله نهائياً.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center px-5 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                إلغاء البلاغ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                {{-- Display details for a REVIEWED or CANCELLED claim --}}
                                <dl class="space-y-4 text-sm text-right">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">الحالة:</dt>
                                        <dd>
                                            <span @class([
                                                'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800' => $claim->claim_status === 'reviewed',
                                                'bg-gray-100 text-gray-800' => $claim->claim_status === 'cancelled',
                                            ])>
                                                @if($claim->claim_status === 'reviewed') تمت المراجعة
                                                @elseif($claim->claim_status === 'cancelled') ملغى
                                                @endif
                                            </span>
                                        </dd>
                                    </div>
                                    @if($claim->reviewer)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">بواسطة:</dt>
                                        <dd class="text-gray-800">{{ $claim->reviewer->first_name }}</dd>
                                    </div>
                                    @endif
                                     @if($claim->reviewed_at)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">تاريخ المراجعة:</dt>
                                        <dd class="text-gray-800">{{ $claim->reviewed_at->isoFormat('LLL') }}</dd>
                                    </div>
                                    @endif
                                     @if($claim->resolutionPost)
                                    <div class="flex justify-between items-center">
                                        <dt class="text-gray-500">منشور الرد المرتبط:</dt>
                                        <dd>
                                            <a href="{{ route('editor.posts.show', $claim->resolutionPost) }}" target="_blank" class="font-semibold text-indigo-600 hover:underline">
                                                {{ $claim->resolutionPost->title }} (#{{ $claim->resolution_post_id }})
                                            </a>
                                        </dd>
                                    </div>
                                    @endif
                                    @if($claim->admin_notes)
                                     <div class="pt-3 mt-3 border-t border-gray-100">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">ملاحظات المراجع:</dt>
                                        <dd class="text-gray-700 bg-gray-50 p-3 rounded-md whitespace-pre-wrap text-xs">{{ $claim->admin_notes }}</dd>
                                    </div>
                                    @endif
                                </dl>
                            @endif
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>

    {{-- Fancybox scripts (optional but recommended for image viewing) --}}
    @once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Fancybox.bind("[data-fancybox]", {});
            });
        </script>
    @endpush
    @endonce
</x-app-layout>