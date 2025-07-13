<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo; // استيراد نموذج معلومات الموقع
use Illuminate\Http\Request; // الاستيراد القياسي
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache; // لاستخدامه في مسح الكاش بعد التحديث (اختياري)
// --- استيراد Form Request ---
use App\Http\Requests\Admin\UpdateSiteInfoRequest;

class SiteInfoController extends Controller
{
    /**
     * عرض نموذج تعديل معلومات الموقع.
     * سيتم جلب أول سجل موجود في جدول site_info أو إنشاء واحد جديد إذا لم يكن موجودًا.
     */
    public function edit(): View
    {
        // جلب أول سجل في الجدول، أو إنشاء سجل جديد بقيم افتراضية إذا كان الجدول فارغًا
        // هذا يضمن دائمًا وجود كائن $siteInfo للعمل معه في الواجهة
        $siteInfo = SiteInfo::firstOrCreate(
            ['info_id' => 1], // يمكن استخدام أي شرط لضمان سجل واحد، أو تركه فارغًا لجلب الأول فقط
            [ // القيم الافتراضية في حال الإنشاء لأول مرة
                'title' => 'معلومات الموقع',
                'content' => '',
                'contact_phone' => '',
                'contact_email' => '',
                'website_url' => url('/')
            ]
        );

        return view('admin.siteinfo.edit', compact('siteInfo'));
    }

    /**
     * تحديث معلومات الموقع في قاعدة البيانات.
     *
     * @param  UpdateSiteInfoRequest  $request
     * @return RedirectResponse
     */
    public function update(UpdateSiteInfoRequest $request): RedirectResponse
    {
        // جلب السجل الوحيد (أو الأول) لتحديثه
        // استخدام findOrFail لضمان وجود السجل أو إرجاع 404 (على الرغم من أننا أنشأناه في edit)
        // نفترض أن المفتاح الأساسي هو 1 بناءً على edit()، يمكنك تغييره إذا لزم الأمر
        $siteInfo = SiteInfo::findOrFail(1);

        // التحقق من الصحة يتم بواسطة UpdateSiteInfoRequest
        $siteInfo->update($request->validated());

        // (اختياري): مسح أي كاش متعلق بمعلومات الموقع إذا كنت تستخدم التخزين المؤقت
        // Cache::forget('site_info');

        // إعادة التوجيه إلى نفس صفحة التعديل مع رسالة نجاح
        return redirect()->route('admin.siteinfo.edit')
                         ->with('success', 'تم تحديث معلومات الموقع بنجاح.');
    }
}