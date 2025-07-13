<x-app-layout>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-24 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">{{ $siteInfo->title ?? 'حول المنصة' }}</h2>
                <div class="mt-8 prose prose-lg text-gray-500 mx-auto text-right" dir="auto">
                    {!! nl2br(e($siteInfo->content ?? '...')) !!}
                </div>
            </div>
            @if($siteInfo)
            <div class="mt-16 text-center border-t border-gray-200 pt-8">
                 <h3 class="text-2xl font-bold text-gray-900 mb-4">للتواصل معنا</h3>
                 <div class="flex flex-col sm:flex-row justify-center items-center gap-x-8 gap-y-4 text-gray-600">
                     @if($siteInfo->contact_phone)
                     <a href="tel:{{ $siteInfo->contact_phone }}" class="flex items-center hover:text-indigo-600">
                         <svg class="h-5 w-5 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" /></svg>
                         <span dir="ltr">{{ $siteInfo->contact_phone }}</span>
                     </a>
                     @endif
                      @if($siteInfo->contact_email)
                     <a href="mailto:{{ $siteInfo->contact_email }}" class="flex items-center hover:text-indigo-600">
                         <svg class="h-5 w-5 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 2.25l7.997 3.634A1 1 0 0017 4.5V15.5a1 1 0 001 1h.5a1 1 0 001-1v-11a1 1 0 00-1-1h-1a1 1 0 00-1 1v.571l-7-3.182a1 1 0 00-.996 0L2.571 3.5H2a1 1 0 00-1 1v11a1 1 0 001 1h.5a1 1 0 001-1V5.884z" /></svg>
                         <span dir="ltr">{{ $siteInfo->contact_email }}</span>
                     </a>
                     @endif
                 </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>