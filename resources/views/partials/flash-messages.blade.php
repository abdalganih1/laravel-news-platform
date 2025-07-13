@if ($message = Session::get('success'))
<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">نجاح!</strong>
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('error'))
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">خطأ!</strong>
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">تنبيه!</strong>
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

@if ($message = Session::get('info'))
<div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">معلومة!</strong>
    <span class="block sm:inline">{{ $message }}</span>
</div>
@endif

{{-- Display general validation errors if not handled field-by-field --}}
{{--
@if ($errors->any())
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">يرجى تصحيح الأخطاء التالية:</strong>
    <ul class="mt-1 list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
--}}