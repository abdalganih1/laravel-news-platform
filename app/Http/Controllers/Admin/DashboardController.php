<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
// --- استيراد النماذج اللازمة للإحصائيات ---
use App\Models\User;
use App\Models\Post;
use App\Models\Claim;
use App\Models\Governorate;
use App\Models\Region;
// -----------------------------------------

class DashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المدير مع الإحصائيات.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // --- جلب بيانات الإحصائيات ---
        $stats = [
            'userCount' => User::count(),
            'adminCount' => User::where('user_role', 'admin')->count(),
            'editorCount' => User::where('user_role', 'editor')->count(),
            'normalUserCount' => User::where('user_role', 'normal')->count(), // أو يمكنك حسابها بالطرح
            'postCount' => Post::count(),
            'realPostCount' => Post::where('post_status', 'real')->count(),
            'fakePostCount' => Post::where('post_status', 'fake')->count(),
            'pendingPostCount' => Post::where('post_status', 'pending_verification')->count(),
            'claimCount' => Claim::count(),
            'pendingClaimCount' => Claim::where('claim_status', 'pending')->count(),
            'reviewedClaimCount' => Claim::where('claim_status', 'reviewed')->count(),
            'governorateCount' => Governorate::count(),
            'regionCount' => Region::count(),
            'pendingEditorRequestsCount' => User::where('user_role', 'pending_editor')->count(),

        ];
        // --------------------------------

        // عرض واجهة لوحة تحكم المدير وتمرير الإحصائيات إليها
        return view('admin.dashboard', compact('stats'));
    }
}