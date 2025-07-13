<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse; // Import JsonResponse

class FavoriteController extends Controller
{
    public function __construct()
    {
        // تطبيق middleware المصادقة على جميع دوال هذا المتحكم
        // باستثناء الدالة التي تتحقق من الحالة (والتي قد يتم استدعاؤها عبر AJAX)
        $this->middleware('auth')->except(['checkStatus']);
    }

    /**
     * عرض قائمة بجميع المنشورات المفضلة للمستخدم الحالي.
     */
    public function index(): View
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('post.user', 'post.region.governorate')->latest()->paginate(10);
        return view('frontend.favorites.index', compact('favorites'));
    }

   /**
     * إضافة منشور إلى المفضلة للمستخدم الحالي.
     * تستخدم بواسطة POST request من نموذج HTML.
     */
    public function store(Post $post): RedirectResponse // Only RedirectResponse return type
    {
        $user = Auth::user();

        // التحقق مما إذا كان موجوداً بالفعل
        $alreadyFavorited = $user->favorites()->where('post_id', $post->post_id)->exists();

        if ($alreadyFavorited) {
             return back()->with('warning', 'هذا المنشور موجود بالفعل في المفضلة.');
        }

        Favorite::create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
        ]);

        // العودة إلى الصفحة السابقة مع رسالة نجاح
        return back()->with('success', 'تمت إضافة المنشور إلى المفضلة.');
    }

    /**
     * إزالة منشور من المفضلة للمستخدم الحالي.
     * تستخدم بواسطة DELETE request من نموذج HTML.
     */
    public function destroy(Post $post): RedirectResponse // Only RedirectResponse return type
    {
        $user = Auth::user();

        $favorite = $user->favorites()->where('post_id', $post->post_id)->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'تمت إزالة المنشور من المفضلة.');
        }

        // إذا لم يتم العثور على سجل المفضلة
        return back()->with('error', 'المنشور ليس في قائمة المفضلة الخاصة بك.');
    }

    /**
     * Check if the current user has favorited a specific post.
     * Used via AJAX.
     * @param Post $post
     * @return JsonResponse
     */
    public function checkStatus(Post $post): JsonResponse
    {
        // Allow guests to check status? Or require auth?
        // Let's require auth as favorites are tied to user
        if (!Auth::check()) {
             return response()->json(['favorited' => false, 'authenticated' => false], 200);
        }

        $favorited = Auth::user()->favorites()->where('post_id', $post->post_id)->exists();

        return response()->json(['favorited' => $favorited, 'authenticated' => true], 200);
    }
}