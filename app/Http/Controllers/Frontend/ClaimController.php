<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// --- استيراد Form Request ---
use App\Http\Requests\Frontend\StoreClaimRequest;

class ClaimController extends Controller
{
    public function __construct()
    {
        // تطبيق middleware المصادقة على جميع دوال هذا المتحكم
        $this->middleware('auth');
    }

    /**
     * عرض نموذج إنشاء بلاغ جديد.
     */
    public function create(): View
    {
        return view('frontend.claims.create');
    }

    /**
     * تخزين البلاغ الجديد.
     */
    public function store(StoreClaimRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id(); // ربط البلاغ بالمستخدم الحالي

        $claim = Claim::create($validatedData);

        // التعامل مع رفع الصور المرفقة مع البلاغ
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('claims/images', 'public');
                $claim->images()->create(['image_url' => $path]);
            }
        }

        return redirect()->route('home') // العودة للصفحة الرئيسية أو لصفحة "بلاغاتي"
                         ->with('success', 'تم إرسال بلاغك بنجاح. شكرًا لمساهمتك.');
    }

    /**
     * عرض قائمة بالبلاغات التي قدمها المستخدم الحالي.
     */
    public function index(): View
    {
        $myClaims = Claim::where('user_id', Auth::id())
                         ->with('resolutionPost')
                         ->latest()
                         ->paginate(10);

        return view('frontend.claims.index', compact('myClaims'));
    }
}