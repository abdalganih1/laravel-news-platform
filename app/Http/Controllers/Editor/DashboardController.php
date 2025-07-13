<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Post; // Import Post model
use App\Models\Claim; // Import Claim model
use Illuminate\Support\Facades\Auth; // Import Auth facade

class DashboardController extends Controller
{
    /**
     * Display the editor's dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Get the currently authenticated editor
        $editor = Auth::user();

        // Fetch statistics relevant to the editor
        $editorStats = [
            // Overall Post Stats (for context)
            'totalPostCount' => Post::count(),
            'realPostCount' => Post::where('post_status', 'real')->count(),
            'fakePostCount' => Post::where('post_status', 'fake')->count(),
            'pendingVerificationPostCount' => Post::where('post_status', 'pending_verification')->count(),

            // Claim Stats (Editor's primary focus)
            'totalClaimCount' => Claim::count(),
            'pendingClaimCount' => Claim::where('claim_status', 'pending')->count(), // Most important stat
            'reviewedClaimCount' => Claim::where('claim_status', 'reviewed')->count(),

            // Optional: Stats specific to this editor
            // 'myPostCount' => Post::where('user_id', $editor->user_id)->count(),
            // 'myReviewedClaimCount' => Claim::where('reviewed_by_user_id', $editor->user_id)->count(),
        ];

        // Return the editor dashboard view with the stats
        return view('editor.dashboard', compact('editorStats'));
    }
}