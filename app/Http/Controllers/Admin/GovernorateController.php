<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Governorate; // استيراد النموذج
use Illuminate\Http\Request; // استيراد Request القياسي (سنستخدم Form Requests للتحقق لاحقًا)
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// --- سنحتاج لإنشاء Form Requests للتحقق من الصحة ---
use App\Http\Requests\Admin\StoreGovernorateRequest;
use App\Http\Requests\Admin\UpdateGovernorateRequest;


use App\Models\Region;

class GovernorateController extends Controller
{
    public function index()
    {
        $governorates = Governorate::with('regions')->latest()->get();
        return view('admin.governorates.index', compact('governorates'));
    }

    /**
     * عرض نموذج إنشاء محافظة جديدة.
     */
    public function create(): View
    {
        return view('admin.governorates.create');
    }

    /**
     * تخزين محافظة جديدة في قاعدة البيانات.
     */
    // استخدم StoreGovernorateRequest للتحقق من الصحة
    public function store(StoreGovernorateRequest $request): RedirectResponse
    {
        // البيانات تم التحقق منها بواسطة StoreGovernorateRequest
        Governorate::create($request->validated());

        // إعادة التوجيه إلى صفحة القائمة مع رسالة نجاح
        return redirect()->route('admin.governorates.index')
                         ->with('success', 'تمت إضافة المحافظة بنجاح.');
    }

    /**
     * Display the specified resource.
     * ملاحظة: هذا المسار تم استثناؤه في web.php بـ except(['show'])
     * لذلك لن يتم استدعاء هذه الدالة عادةً من خلال المسارات المعرفة.
     */
    public function show(Governorate $governorate): View // تم تغيير string $id إلى Model Binding
    {
        // نظريًا، ستعرض تفاصيل المحافظة هنا
        // return view('admin.governorates.show', compact('governorate'));
        abort(404); // أو ببساطة أرجع خطأ 404 لأن المسار غير مستخدم
    }

    /**
     * عرض نموذج تعديل محافظة موجودة.
     */
    public function edit(Governorate $governorate): View // استخدام Model Binding
    {
        // تم العثور على المحافظة تلقائيًا بواسطة Model Binding
        return view('admin.governorates.edit', compact('governorate'));
    }

    /**
     * تحديث بيانات محافظة موجودة في قاعدة البيانات.
     */
    // استخدم UpdateGovernorateRequest للتحقق من الصحة
    public function update(UpdateGovernorateRequest $request, Governorate $governorate): RedirectResponse
    {
        // البيانات تم التحقق منها بواسطة UpdateGovernorateRequest
        // والمحافظة تم العثور عليها بواسطة Model Binding
        $governorate->update($request->validated());

        // إعادة التوجيه إلى صفحة القائمة مع رسالة نجاح
        return redirect()->route('admin.governorates.index')
                         ->with('success', 'تم تعديل المحافظة بنجاح.');
    }

    /**
     * حذف محافظة من قاعدة البيانات.
     */
    public function destroy(Governorate $governorate)
    {
        // Delete related regions first
        $governorate->regions()->delete();
        
        $governorate->delete();
        return back()->with('success', 'تم حذف المحافظة وجميع مناطقها بنجاح.');
    }

    public function storeRegion(Request $request, Governorate $governorate)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $governorate->regions()->create($request->only('name'));
        return back()->with('success', 'تمت إضافة المنطقة بنجاح.');
    }

    public function updateRegion(Request $request, Region $region)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $region->update($request->only('name'));
        return back()->with('success', 'تم تعديل المنطقة بنجاح.');
    }

    public function destroyRegion(Region $region)
    {
        $region->delete();
        return back()->with('success', 'تم حذف المنطقة بنجاح.');
    }
}