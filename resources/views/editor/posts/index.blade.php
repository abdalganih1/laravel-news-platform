<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إدارة المنشورات') }}
            </h2>
             <a href="{{ route('editor.posts.create') }}" class="inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 active:bg-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 <span>إضافة منشور</span>
                 <svg class="w-4 h-4 ms-2 -me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-72 xl:w-80 flex-shrink-0">
                    @include('partials.editor.sidebar') {{-- Editor Sidebar --}}
                </div>
                <div class="flex-grow space-y-6">
                    @include('partials.flash-messages')

                    {{-- Optional Filters --}}
                    {{-- <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 mb-6"> ... Filters UI ... </div> --}}

                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 bg-white border-b border-gray-200">
                             <h3 class="text-lg font-medium text-gray-900 mb-4 text-right">قائمة المنشورات</h3>
                            @if($posts->isEmpty())
                                <p class="text-gray-500 text-center py-10">لا توجد منشورات لعرضها حاليًا.</p>
                            @else
                                <div class="overflow-x-auto relative">
                                    <table class="w-full text-sm text-right text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th scope="col" class="py-3 px-6">العنوان</th>
                                                <th scope="col" class="py-3 px-6">الناشر</th>
                                                <th scope="col" class="py-3 px-6">الحالة</th>
                                                 <th scope="col" class="py-3 px-6">المنطقة</th>
                                                <th scope="col" class="py-3 px-6">تاريخ النشر</th>
                                                <th scope="col" class="py-3 px-6">الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <th scope="row" class="py-4 px-6 font-medium text-gray-900 max-w-sm truncate" title="{{ $post->title }}">
                                                    {{ $post->title }}
                                                </th>
                                                <td class="py-4 px-6">{{ $post->user->first_name ?? 'N/A' }}</td>
                                                <td class="py-4 px-6">
                                                     <span @class([
                                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                        'bg-blue-100 text-blue-800' => $post->post_status === 'pending_verification',
                                                        'bg-green-100 text-green-800' => $post->post_status === 'real',
                                                        'bg-red-100 text-red-800' => $post->post_status === 'fake',
                                                    ])>
                                                        @if($post->post_status === 'pending_verification') قيد التحقق
                                                        @elseif($post->post_status === 'real') حقيقي
                                                        @elseif($post->post_status === 'fake') مزيف
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">{{ $post->region->name ?? '-' }}</td>
                                                <td class="py-4 px-6 whitespace-nowrap" title="{{ $post->created_at }}">{{ $post->created_at->diffForHumans() }}</td>
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center justify-start space-x-2 space-x-reverse">
                                                        <a href="{{ route('editor.posts.show', $post) }}" class="text-blue-600 hover:text-blue-800 px-1" title="عرض"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                                        <a href="{{ route('editor.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-800 px-1" title="تعديل"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                                        <form action="{{ route('editor.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنشور وجميع وسائطه المرتبطة؟');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 px-1" title="حذف"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6">
                                    {{ $posts->links() }}
                                </div>
                             @endif
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>