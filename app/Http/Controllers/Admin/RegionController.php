<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region; // استيراد نموذج المنطقة
use App\Models\Governorate; // استيراد نموذج المحافظة (للقوائم المنسدلة)
use Illuminate\Http\Request; // الاستيراد القياسي
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// --- استيراد Form Requests ---
use App\Http\Requests\Admin\StoreRegionRequest;
use App\Http\Requests\Admin\UpdateRegionRequest;


class RegionController extends Controller
{
    /**
     * عرض قائمة بجميع المناطق مع المحافظات المرتبطة بها.
     */
    public function index(): View
    {
        // جلب المناطق مع تحميل المحافظة المرتبطة بها (eager loading)
        // الترتيب حسب اسم المحافظة ثم اسم المنطقة
        $regions = Region::with('governorate')
                         ->join('governorates', 'regions.governorate_id', '=', 'governorates.governorate_id')
                         ->orderBy('governorates.name')
                         ->orderBy('regions.name')
                         ->select('regions.*') // تحديد أعمدة المناطق لتجنب التعارض
                         ->paginate(15); // عرض 15 منطقة في كل صفحة

        return view('admin.regions.index', compact('regions'));
    }

    /**
     * عرض نموذج إنشاء منطقة جديدة.
     */
    public function create(): View
    {
        // جلب قائمة بجميع المحافظات لعرضها في القائمة المنسدلة
        $governorates = Governorate::orderBy('name')->get(['governorate_id', 'name']);
        return view('admin.regions.create', compact('governorates'));
    }

    /**
     * تخزين منطقة جديدة في قاعدة البيانات.
     */
    public function store(StoreRegionRequest $request): RedirectResponse
    {
        // التحقق من الصحة يتم بواسطة StoreRegionRequest
        Region::create($request->validated());

        return redirect()->route('admin.regions.index')
                         ->with('success', 'تمت إضافة المنطقة بنجاح.');
    }

    /**
     * Display the specified resource.
     * (غير مستخدم بسبب except(['show']) في web.php)
     */
    public function show(Region $region): View
    {
        abort(404);
    }

    /**
     * عرض نموذج تعديل منطقة موجودة.
     */
    public function edit(Region $region): View // استخدام Model Binding
    {
        // جلب قائمة بجميع المحافظات للقائمة المنسدلة
        $governorates = Governorate::orderBy('name')->get(['governorate_id', 'name']);
        // تمرير المنطقة المحددة وقائمة المحافظات إلى الواجهة
        return view('admin.regions.edit', compact('region', 'governorates'));
    }

    /**
     * تحديث بيانات منطقة موجودة في قاعدة البيانات.
     */
    public function update(UpdateRegionRequest $request, Region $region): RedirectResponse
    {
        // التحقق والنموذج يتم بواسطة الحقن و Model Binding
        $region->update($request->validated());

        return redirect()->route('admin.regions.index')
                         ->with('success', 'تم تعديل المنطقة بنجاح.');
    }

    /**
     * حذف منطقة من قاعدة البيانات.
     */
    public function destroy(Region $region): RedirectResponse
    {
        // المناطق عادة لا تملك قيودًا تمنع حذفها بشكل مباشر (مثل المنشورات المرتبطة بها تستخدم SET NULL)
        // لكن لا يزال من الجيد وجود try-catch للأخطاء غير المتوقعة.
        try {
            $region->delete();
            return redirect()->route('admin.regions.index')
                             ->with('success', 'تم حذف المنطقة بنجاح.');

        } catch (\Illuminate\Database\QueryException $e) {
            // يمكن التقاط أخطاء قاعدة بيانات أخرى هنا إذا لزم الأمر
             return redirect()->route('admin.regions.index')
                             ->with('error', 'حدث خطأ في قاعدة البيانات أثناء محاولة الحذف: ' . $e->getMessage());
        } catch (\Exception $e) {
             return redirect()->route('admin.regions.index')
                             ->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }
}