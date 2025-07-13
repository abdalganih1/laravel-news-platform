<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة المستخدمين') }}
            </h2>
             <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 <span>إضافة مستخدم</span>
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
                         {{-- Filters --}}
                         <div class="p-4 bg-white border-b border-gray-200">
                             <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-center gap-4 justify-end">
                                <label for="role_filter" class="text-sm font-medium text-gray-700 whitespace-nowrap">عرض حسب الدور:</label>
                                <select name="role_filter" id="role_filter" onchange="this.form.submit()"
                                        class="block w-full sm:w-auto border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm text-right">
                                    <option value="">كل الأدوار</option>
                                    @foreach($availableRoles as $role)
                                        <option value="{{ $role }}" {{ request('role_filter') == $role ? 'selected' : '' }}>
                                            @if($role === 'admin') مدير النظام
                                            @elseif($role === 'editor') محرر محتوى
                                            @elseif($role === 'normal') مستخدم عادي
                                            @elseif($role === 'pending_editor') طلب محرر (معلق)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                             </form>
                         </div>

                        <div class="p-6 bg-white border-b border-gray-200">
                             <h3 class="text-lg font-medium text-gray-900 mb-4 text-right">قائمة المستخدمين</h3>
                            @if($users->isEmpty())
                                <p class="text-gray-500 text-center py-6">لا يوجد مستخدمون لعرضهم حاليًا.</p>
                            @else
                                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <table class="w-full text-sm text-right text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                {{-- Place Image header BEFORE Name header --}}
                                                <th scope="col" class="py-3 px-6">الصورة</th>
                                                <th scope="col" class="py-3 px-6">الاسم</th>
                                                <th scope="col" class="py-3 px-6">البريد الإلكتروني</th>
                                                <th scope="col" class="py-3 px-6">الدور</th>
                                                <th scope="col" class="py-3 px-6">المحافظة</th>
                                                <th scope="col" class="py-3 px-6">الإجراءات</th>
                                            </tr>
                                        </thead>
                                       <tbody>
                                    @foreach ($users as $user)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                         {{-- Place Image cell BEFORE Name cell --}}
                                         <td class="py-4 px-6">
                                            <div class="flex items-center justify-center">
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                     src="{{ $user->profile_image_url }}"
                                                     alt="{{ $user->first_name }} {{ $user->last_name }}">
                                            </div>
                                         </td>
                                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </th>
                                                <td class="py-4 px-6 ltr:text-left" dir="ltr">{{ $user->email }}</td> {{-- Ensure email is LTR --}}
                                                <td class="py-4 px-6">
                                                    <span @class([
                                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                        'bg-red-100 text-red-800' => $user->user_role === 'admin',
                                                        'bg-yellow-100 text-yellow-800' => $user->user_role === 'editor',
                                                        'bg-green-100 text-green-800' => $user->user_role === 'normal',
                                                         'bg-blue-100 text-blue-800' => $user->user_role === 'pending_editor', // Add style for pending
                                                    ])>
                                                        @if($user->user_role === 'admin') مدير النظام
                                                        @elseif($user->user_role === 'editor') محرر محتوى
                                                        @elseif($user->user_role === 'normal') مستخدم عادي
                                                        @elseif($user->user_role === 'pending_editor') طلب محرر
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">{{ $user->governorate->name ?? '-' }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center justify-start space-x-3 space-x-reverse">
                                                        {{-- Show Button --}}
                                                        <a href="{{ route('admin.users.show', $user) }}" class="font-medium text-blue-600 hover:text-blue-800">عرض</a>
                                                        {{-- Edit Button --}}
                                                        <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-indigo-600 hover:text-indigo-800">تعديل</a>
                                                        {{-- Delete Button Form --}}
                                                         @if(Auth::id() !== $user->user_id)
                                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="font-medium text-red-600 hover:text-red-800">حذف</button>
                                                            </form>
                                                         @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6">
                                    {{ $users->links() }}
                                </div>
                             @endif
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>