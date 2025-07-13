<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Claim; // استيراد نموذج البلاغ
use App\Models\Post;  // استيراد نموذج المنشور (للاختيار منه)
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    /**
     * عرض قائمة بالبلاغات مع إمكانية الفلترة.
     */
    public function index(Request $request): View
    {
        // الفلتر الافتراضي هو البلاغات المعلقة
        $statusFilter = $request->input('status', 'pending');

        $claimsQuery = Claim::with(['user', 'resolutionPost', 'images']) // جلب العلاقات الجديدة
                             ->orderBy('created_at', 'desc');

        if ($statusFilter && in_array($statusFilter, ['pending', 'reviewed', 'cancelled'])) {
            $claimsQuery->where('claim_status', $statusFilter);
        }

        $claims = $claimsQuery->paginate(15);
        $availableStatuses = ['pending', 'reviewed', 'cancelled'];

        return view('editor.claims.index', compact('claims', 'statusFilter', 'availableStatuses'));
    }

    /**
     * عرض تفاصيل بلاغ معين للمراجعة.
     */
    public function show(Claim $claim): View
    {
        // تحميل العلاقات اللازمة
        $claim->loadMissing(['user', 'reviewer', 'images', 'resolutionPost']);

        // جلب المنشورات الحقيقية لاختيار منشور الرد (التحقق/التكذيب)
        $resolutionPosts = Post::where('post_status', 'real')
                                ->orderBy('created_at', 'desc')
                                ->limit(100)
                                ->get(['post_id', 'title']);

        return view('editor.claims.show', compact('claim', 'resolutionPosts'));
    }


    /**
     * معالجة مراجعة البلاغ وربطه بمنشور الرد.
     * لم نعد نستخدم Form Request هنا لأن التحقق بسيط ويمكن القيام به مباشرة.
     */
    public function review(Request $request, Claim $claim): RedirectResponse
    {
        // تأكد أن البلاغ ما زال معلقًا
        if ($claim->claim_status !== 'pending') {
            return redirect()->route('editor.claims.index')->with('warning', 'هذا البلاغ تمت مراجعته بالفعل.');
        }

        // التحقق من المدخلات
        $validatedData = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'resolution_post_id' => ['nullable', 'integer', 'exists:posts,post_id'],
        ], [
            'admin_notes.max' => 'ملاحظات المحرر يجب ألا تتجاوز 1000 حرف.',
            'resolution_post_id.exists' => 'منشور الرد المحدد غير موجود أو غير صالح.',
        ]);

        // تحديث البلاغ
        $claim->claim_status = 'reviewed';
        $claim->admin_notes = $validatedData['admin_notes'];
        $claim->resolution_post_id = $validatedData['resolution_post_id']; // ربط بمنشور الرد
        $claim->reviewed_by_user_id = Auth::id();
        $claim->reviewed_at = now();
        $claim->save();

        return redirect()->route('editor.claims.show', $claim) // العودة لنفس الصفحة بعد التحديث
                         ->with('success', 'تمت مراجعة البلاغ بنجاح.');
    }


    /**
     * (اختياري) إلغاء بلاغ.
     */
    // app/Http/Controllers/Editor/ClaimController.php

/**
 * (اختياري) إلغاء بلاغ.
 */
public function cancel(Claim $claim): RedirectResponse
{
    if ($claim->claim_status !== 'pending') {
        return redirect()->route('editor.claims.index')->with('warning', 'لا يمكن إلغاء بلاغ تمت مراجعته.');
    }

    $claim->claim_status = 'cancelled';
    $claim->reviewed_by_user_id = Auth::id();
    $claim->reviewed_at = now();
    $claim->admin_notes = ($claim->admin_notes ? $claim->admin_notes . "\n" : '') . "تم الإلغاء بواسطة المحرر لعدم الأهمية أو التكرار.";
    $claim->save();

    return redirect()->route('editor.claims.index')->with('success', 'تم إلغاء البلاغ بنجاح.');
}
}