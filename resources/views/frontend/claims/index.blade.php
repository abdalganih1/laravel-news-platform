<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('بلاغاتي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($myClaims->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                              <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">لم تقم بتقديم أي بلاغات بعد</h3>
                            <p class="mt-1 text-sm text-gray-500">يمكنك المساعدة في مكافحة الأخبار المزيفة عن طريق تقديم بلاغ جديد.</p>
                            <div class="mt-6">
                              <a href="{{ route('frontend.claims.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                تقديم بلاغ جديد
                              </a>
                            </div>
                        </div>
                    @else
                        <ul role="list" class="divide-y divide-gray-200">
                             @foreach($myClaims as $claim)
                                <li>
                                    <div class="block hover:bg-gray-50 px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate text-right">
                                                {{ $claim->title ?: 'بلاغ بدون عنوان' }}
                                            </p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p @class([
                                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                    'bg-yellow-100 text-yellow-800' => $claim->claim_status === 'pending',
                                                    'bg-green-100 text-green-800' => $claim->claim_status === 'reviewed',
                                                    'bg-gray-100 text-gray-800' => $claim->claim_status === 'cancelled',
                                                ])>
                                                    @if($claim->claim_status === 'pending') معلق
                                                    @elseif($claim->claim_status === 'reviewed') تمت المراجعة
                                                    @else ملغى
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex text-right">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                    </svg>
                                                     تم التقديم في: {{ $claim->created_at->isoFormat('D MMMM YYYY') }}
                                                </p>
                                            </div>
                                            @if($claim->resolutionPost)
                                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                الرد الرسمي:
                                                <a href="{{ route('frontend.posts.show', $claim->resolutionPost) }}" class="font-medium text-indigo-600 hover:text-indigo-500 ml-2">
                                                    عرض المنشور
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                             @endforeach
                        </ul>
                         <div class="mt-6">
                            {{ $myClaims->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>