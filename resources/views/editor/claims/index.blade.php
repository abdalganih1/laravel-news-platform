<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مراجعة البلاغات') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.editor.sidebar')
                </div>
                <div class="flex-grow space-y-6">
                    @include('partials.flash-messages')

                    {{-- Filters --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                         <form method="GET" action="{{ route('editor.claims.index') }}" class="flex flex-col sm:flex-row items-center gap-4 justify-end">
                            <label for="status" class="text-sm font-medium text-gray-700 whitespace-nowrap">عرض حسب الحالة:</label>
                            <select name="status" id="status" onchange="this.form.submit()"
                                    class="block w-full sm:w-auto border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm text-right">
                                <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>الكل</option>
                                @foreach($availableStatuses as $status)
                                    <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                        @if($status === 'pending') معلق
                                        @elseif($status === 'reviewed') تمت المراجعة
                                        @elseif($status === 'cancelled') ملغى
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                         </form>
                    </div>

                    {{-- Claims Table --}}
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 bg-white">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 text-right border-b border-gray-200 pb-3">
                                قائمة البلاغات
                                @if($statusFilter && $statusFilter !== 'all')
                                 (@if($statusFilter === 'pending') المعلقة @elseif($statusFilter === 'reviewed') المراجعة @elseif($statusFilter === 'cancelled') الملغاة @endif)
                                @endif
                            </h3>

                            @if($claims->isEmpty())
                                <p class="text-gray-500 text-center py-6">لا توجد بلاغات تطابق الفلتر الحالي.</p>
                            @else
                                <div class="overflow-x-auto relative">
                                    <table class="w-full text-sm text-right text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">عنوان البلاغ</th>
                                                <th scope="col" class="py-3 px-6">المستخدم المبلغ</th>
                                                <th scope="col" class="py-3 px-6">الحالة</th>
                                                <th scope="col" class="py-3 px-6">تاريخ البلاغ</th>
                                                <th scope="col" class="py-3 px-6">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($claims as $claim)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="py-4 px-6 font-mono">{{ $claim->claim_id }}</td>
                                                <td class="py-4 px-6 max-w-sm truncate">
                                                    <span class="font-medium text-gray-800" title="{{ $claim->title }}">
                                                       {{ Str::limit($claim->title, 50) ?: 'بلاغ بدون عنوان' }}
                                                    </span>
                                                    @if($claim->external_url)
                                                    <a href="{{ $claim->external_url }}" target="_blank" class="text-xs text-blue-500 hover:underline block ltr text-left" title="{{ $claim->external_url }}">
                                                        {{ Str::limit($claim->external_url, 40) }}
                                                    </a>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">{{ $claim->user->first_name ?? 'مستخدم محذوف' }}</td>
                                                <td class="py-4 px-6">
                                                    <span @class([
                                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                        'bg-yellow-100 text-yellow-800' => $claim->claim_status === 'pending',
                                                        'bg-green-100 text-green-800' => $claim->claim_status === 'reviewed',
                                                        'bg-gray-100 text-gray-800' => $claim->claim_status === 'cancelled',
                                                    ])>
                                                        @if($claim->claim_status === 'pending') معلق
                                                        @elseif($claim->claim_status === 'reviewed') تمت المراجعة
                                                        @elseif($claim->claim_status === 'cancelled') ملغى
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap" title="{{ $claim->created_at }}">{{ $claim->created_at->diffForHumans() }}</td>
                                                <td class="py-4 px-6">
                                                     <a href="{{ route('editor.claims.show', $claim) }}" class="font-medium text-cyan-600 hover:text-cyan-800">
                                                        @if($claim->claim_status === 'pending') مراجعة @else عرض @endif
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6">
                                    {{ $claims->appends(['status' => $statusFilter])->links() }}
                                </div>
                             @endif
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>