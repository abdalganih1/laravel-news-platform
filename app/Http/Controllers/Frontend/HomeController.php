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
        $latestPosts = Post::where('post_status', 'real')
                        ->with(['user', 'region.governorate', 'favorites']) // <-- أضف 'favorites' هنا
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();

        $recentlyDebunked = Post::where('post_status', 'fake')
                                ->whereNotNull('corrected_post_id')
                                ->with(['correction', 'favorites']) // <-- أضف 'favorites' هنا أيضاً إذا أردت الزر في بطاقة التكذيب
                                ->orderBy('updated_at', 'desc')
                                ->limit(5)
                                ->get();

        return view('frontend.home', compact('latestPosts', 'recentlyDebunked'));
    }
}