<x-app-layout>
    <x-slot name="header">
         <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('عرض المستخدم:') }} {{ $user->first_name }} {{ $user->last_name }}
            </h2>
             <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                 <svg class="w-4 h-4 me-2 -ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                 <span>العودة للقائمة</span>
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                 <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.admin.sidebar')
                </div>
                 <div class="flex-grow">
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 bg-white">
                            {{-- Header with Image and Name --}}
                            <div class="flex items-center justify-end gap-4 border-b border-gray-200 pb-6 mb-6">
                                {{-- User Name and Details --}}
                                <div class="text-right flex-grow">
                                     <h3 class="text-2xl font-semibold text-gray-900 leading-tight">{{ $user->first_name }} {{ $user->last_name }}</h3>
                                     <p class="text-sm text-gray-600 mt-1">{{ $user->user_role }} @if($user->governorate) في محافظة {{ $user->governorate->name }} @endif</p>
                                </div>
                                {{-- Profile Image --}}
                                <div class="flex-shrink-0">
                                     <img class="h-20 w-20 rounded-full object-cover border-2 border-indigo-500"
                                          src="{{ $user->profile_image_url }}"
                                          alt="{{ $user->first_name }} {{ $user->last_name }}">
                                </div>
                            </div>

                            {{-- User Details --}}
                            <dl class="space-y-4 text-sm">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">المعرف (ID)</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right font-mono">{{ $user->user_id }}</dd>
                                </div>
                                 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">البريد الإلكتروني</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right ltr" dir="ltr">{{ $user->email }}</dd>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">رقم الهاتف</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right ltr" dir="ltr">{{ $user->phone_number ?? '-' }}</dd>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">دور المستخدم</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right">
                                        <span @class([
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-red-100 text-red-800' => $user->user_role === 'admin',
                                            'bg-yellow-100 text-yellow-800' => $user->user_role === 'editor',
                                            'bg-green-100 text-green-800' => $user->user_role === 'normal',
                                            'bg-blue-100 text-blue-800' => $user->user_role === 'pending_editor',
                                        ])>
                                            @if($user->user_role === 'admin') مدير النظام
                                            @elseif($user->user_role === 'editor') محرر محتوى
                                            @elseif($user->user_role === 'normal') مستخدم عادي
                                            @elseif($user->user_role === 'pending_editor') طلب محرر (معلق)
                                            @endif
                                        </span>
                                    </dd>
                                </div>
                                 @if($user->governorate)
                                 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">المحافظة</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right">{{ $user->governorate->name }}</dd>
                                </div>
                                 @endif
                                 @if($user->date_of_birth)
                                 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">تاريخ الميلاد</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right">{{ $user->date_of_birth->format('Y-m-d') }}</dd>
                                </div>
                                 @endif
                                @if($user->notes)
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-start pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">ملاحظات</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right whitespace-pre-wrap">{{ $user->notes }}</dd>
                                </div>
                                @endif
                                 <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">تاريخ الانضمام</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right" title="{{ $user->created_at }}">{{ $user->created_at->isoFormat('LLL') }} ({{ $user->created_at->diffForHumans() }})</dd>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center pt-4 border-t border-gray-100">
                                    <dt class="text-sm font-medium text-gray-500 text-right sm:col-span-1">آخر تحديث</dt>
                                    <dd class="text-sm text-gray-900 sm:col-span-2 text-right" title="{{ $user->updated_at }}">{{ $user->updated_at->isoFormat('LLL') }} ({{ $user->updated_at->diffForHumans() }})</dd>
                                </div>
                             </dl>

                             {{-- Action Button --}}
                             <div class="mt-8 pt-6 flex justify-start border-t border-gray-200">
                                 <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">تعديل بيانات المستخدم</a>
                             </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>