<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة المناطق') }}
            </h2>
             <a href="{{ route('admin.regions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 <span>إضافة منطقة</span>
                 <svg class="w-4 h-4 ms-2 -me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.admin.sidebar')
                </div>
                <div class="flex-grow space-y-6">
                    @include('partials.flash-messages')
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 text-right">قائمة المناطق</h3>
                            @if($regions->isEmpty())
                                <p class="text-gray-500 text-center">لا توجد مناطق لعرضها حاليًا.</p>
                            @else
                                <div class="overflow-x-auto relative">
                                    <table class="w-full text-sm text-right text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th scope="col" class="py-3 px-6">#</th>
                                                <th scope="col" class="py-3 px-6">اسم المنطقة</th>
                                                <th scope="col" class="py-3 px-6">المحافظة</th>
                                                <th scope="col" class="py-3 px-6">إحداثيات GPS</th>
                                                <th scope="col" class="py-3 px-6">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($regions as $region)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="py-4 px-6">{{ $region->region_id }}</td>
                                                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                    {{ $region->name }}
                                                </th>
                                                <td class="py-4 px-6">{{ $region->governorate->name ?? 'N/A' }}</td> {{-- Access related governorate name --}}
                                                <td class="py-4 px-6">{{ $region->gps_coordinates ?? '-' }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center justify-start space-x-3 space-x-reverse">
                                                        <a href="{{ route('admin.regions.edit', $region) }}" class="font-medium text-indigo-600 hover:text-indigo-800">تعديل</a>
                                                        <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="font-medium text-red-600 hover:text-red-800">حذف</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6">
                                    {{ $regions->links() }}
                                </div>
                             @endif
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>