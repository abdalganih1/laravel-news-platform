<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post; // Import Post model
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية للموقع.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $latestPosts = Post::whereIn('post_status', ['real', 'fake'])
                        ->with(['user', 'region.governorate', 'favorites'])
                        ->orderBy('created_at', 'desc')
                        ->limit(12)
                        ->get();

        return view('frontend.home', compact('latestPosts'));
    }
}