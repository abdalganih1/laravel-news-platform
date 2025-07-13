<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة المحافظات والمناطق') }}
            </h2>
             <a href="{{ route('admin.governorates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 <span>إضافة محافظة</span>
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

                <div class="flex-grow space-y-6" x-data="{ openModal: false, editModal: false, selectedGovernorate: null, selectedRegion: {} }">
                    @include('partials.flash-messages')

                    <div class="space-y-4">
                        @if($governorates->isEmpty())
                            <p class="text-gray-500 text-center bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                                لا توجد محافظات لعرضها حاليًا.
                            </p>
                        @else
                            @foreach ($governorates as $governorate)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm" x-data="{ expanded: false }">
                                    <div class="p-4 cursor-pointer flex justify-between items-center" @click="expanded = !expanded">
                                        <div class="flex items-center gap-3">
                                            <svg class="w-6 h-6 text-gray-400 transform transition-transform" :class="{'rotate-90': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            <h3 class="font-semibold text-lg text-gray-800">{{ $governorate->name }}</h3>
                                        </div>
                                        <div class="flex items-center justify-start space-x-3 space-x-reverse">
                                            <button @click.stop="openModal = true; selectedGovernorate = {{ $governorate->governorate_id }}" class="font-medium text-sm text-green-600 hover:text-green-800">إضافة منطقة</button>
                                            <a href="{{ route('admin.governorates.edit', $governorate) }}" class="font-medium text-sm text-indigo-600 hover:text-indigo-800">تعديل المحافظة</a>
                                            <form action="{{ route('admin.governorates.destroy', $governorate) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-sm text-red-600 hover:text-red-800">حذف المحافظة</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div x-show="expanded" x-collapse class="border-t border-gray-200 p-4">
                                        @if($governorate->regions->isEmpty())
                                            <p class="text-gray-500 px-4">لا توجد مناطق مضافة لهذه المحافظة.</p>
                                        @else
                                            <ul class="space-y-2">
                                                @foreach ($governorate->regions as $region)
                                                    <li class="flex justify-between items-center p-2 rounded-md hover:bg-gray-50">
                                                        <span>- {{ $region->name }}</span>
                                                        <div class="flex items-center space-x-2 space-x-reverse">
                                                            <button @click.stop="editModal = true; selectedRegion = {{ json_encode($region) }}" class="font-medium text-xs text-yellow-600 hover:text-yellow-800">تعديل</button>
                                                            <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="font-medium text-xs text-red-600 hover:text-red-800">حذف</button>
                                                            </form>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Add Region Modal -->
                    <div x-show="openModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" @click.self="openModal = false" style="display: none;">
                        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                            <h3 class="text-lg font-bold mb-4">إضافة منطقة جديدة</h3>
                            <form :action="`/admin/governorates/${selectedGovernorate}/regions`" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 text-right">اسم المنطقة</label>
                                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                                <div class="flex justify-start space-x-4 space-x-reverse">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">حفظ</button>
                                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">إلغاء</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Region Modal -->
                    <div x-show="editModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" @click.self="editModal = false" style="display: none;">
                        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                            <h3 class="text-lg font-bold mb-4">تعديل المنطقة</h3>
                            <form :action="`/admin/regions/${selectedRegion.region_id}`" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700 text-right">اسم المنطقة</label>
                                    <input type="text" name="name" id="edit_name" x-model="selectedRegion.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>
                                <div class="flex justify-start space-x-4 space-x-reverse">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">تحديث</button>
                                    <button type="button" @click="editModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">إلغاء</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
