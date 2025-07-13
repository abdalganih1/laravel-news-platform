<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * عرض صفحة "حولنا".
     */
    public function about(): View
    {
        // جلب معلومات الموقع لعرضها في الصفحة
        // استخدام Caching هنا فكرة جيدة لتحسين الأداء
        $siteInfo = SiteInfo::first();
        return view('frontend.pages.about', compact('siteInfo'));
    }
}