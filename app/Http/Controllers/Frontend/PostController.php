<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Auth;
class PostController extends Controller
{
    /**
     * عرض قائمة بجميع المنشورات الحقيقية مع فلترة.
     */
    public function index(Request $request): View
    {
        $query = Post::where('post_status', 'real')
                 ->with(['user', 'region.governorate', 'favorites']); // <-- أضف 'favorites' هنا

        // فلترة حسب الكلمة المفتاحية (البحث)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('text_content', 'LIKE', "%{$search}%");
            });
        }

        // فلترة حسب المحافظة
        if ($request->filled('governorate_id')) {
            $governorateId = $request->input('governorate_id');
            $query->whereHas('region', function ($q) use ($governorateId) {
                $q->where('governorate_id', $governorateId);
            });
        }

        $posts = $query->latest()->paginate(12)->withQueryString();
        $governorates = Governorate::orderBy('name')->get();

        return view('frontend.posts.index', compact('posts', 'governorates'));
    }

    /**
     * عرض منشور واحد.
     */
    public function show(Post $post): View
    {
        // عرض المنشور فقط إذا كان حقيقيًا (أو إذا كان مزيفًا وله تصحيح)
        // أو إذا كان المستخدم الحالي محررًا/مديرًا
        if (
            $post->post_status !== 'real' &&
            !($post->post_status === 'fake' && $post->correction) &&
            !(Auth::check() && in_array(Auth::user()->user_role, ['editor', 'admin']))
        ) {
            abort(404);
        }

    $post->loadMissing(['user', 'region.governorate', 'images', 'videos', 'correction', 'correctedPosts', 'favorites']); // <-- أضف 'favorites' هنا

        // جلب منشورات أخرى ذات صلة (مثال: من نفس المحافظة)
        $relatedPosts = Post::where('post_status', 'real')
                            ->where('post_id', '!=', $post->post_id)
                            ->when($post->region, function($q) use ($post) {
                                $q->whereHas('region', function ($subq) use ($post) {
                                    $subq->where('governorate_id', $post->region->governorate_id);
                                });
                            })
                            ->limit(3)
                            ->latest()
                            ->get();

        return view('frontend.posts.show', compact('post', 'relatedPosts'));
    }
}