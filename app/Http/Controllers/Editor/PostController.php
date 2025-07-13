<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Region;
use App\Models\Claim;
use App\Models\PostImage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Editor\StorePostRequest;
use App\Http\Requests\Editor\UpdatePostRequest;
// --- استيراد الكلاسات الصحيحة للإصدار 3 ---
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostController extends Controller
{
    private array $allowedStatuses = ['pending_verification', 'fake', 'real'];

    /**
     * Display a listing of the posts.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['user', 'region.governorate'])
                     ->orderBy('created_at', 'desc');

        $posts = $query->paginate(15);
        return view('editor.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(Claim $claim = null): View
    {
        $regions = Region::with('governorate')->orderBy('governorate_id')->orderBy('name')->get();
        $groupedRegions = $regions->groupBy('governorate.name');
        return view('editor.posts.create', compact('groupedRegions', 'claim'));
    }

    /**
     * Store a newly created post in storage with image processing.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();
        $post = Post::create($validatedData);

        // --- Handle Image Uploads with Intervention Image v3 ---
        if ($request->hasFile('images')) {
            // إنشاء مدير الصور مرة واحدة فقط
            $manager = new ImageManager(new Driver());
            foreach ($request->file('images') as $imageFile) {
                // تمرير المدير إلى الدالة المساعدة
                $this->processAndStoreImage($imageFile, $post, $manager);
            }
        }

        // --- Handle Video Uploads ---
        if ($request->hasFile('videos')) {
             foreach ($request->file('videos') as $videoFile) {
                $path = $videoFile->store('posts/videos', 'public');
                $post->videos()->create(['video_url' => $path]);
            }
        }

        // --- Update the source claim if this post is a response to it ---
        if ($request->filled('source_claim_id')) {
            $claim = Claim::find($request->input('source_claim_id'));
            if ($claim && $claim->claim_status === 'pending') {
                $claim->update([
                    'resolution_post_id' => $post->post_id,
                    'claim_status' => 'reviewed',
                    'reviewed_by_user_id' => Auth::id(),
                    'reviewed_at' => now(),
                    'admin_notes' => ($claim->admin_notes ? $claim->admin_notes . "\n" : '') . "تم إنشاء المنشور #" . $post->post_id . " للرد على هذا البلاغ."
                ]);
                return redirect()->route('editor.claims.show', $claim)
                                 ->with('success', 'تم إنشاء المنشور وربط الرد بالبلاغ بنجاح.');
            }
        }

        return redirect()->route('editor.posts.index')
                         ->with('success', 'تم نشر المنشور بنجاح.');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post): View
    {
        $post->loadMissing(['user', 'region.governorate', 'images', 'videos', 'correction', 'correctedPosts']);
        return view('editor.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        $regions = Region::with('governorate')->orderBy('governorate_id')->orderBy('name')->get();
        $groupedRegions = $regions->groupBy('governorate.name');
        $statuses = $this->allowedStatuses;
        $realPosts = Post::where('post_status', 'real')
                        ->where('post_id', '!=', $post->post_id)
                        ->orderBy('created_at', 'desc')
                        ->limit(100)
                        ->get(['post_id', 'title']);

        return view('editor.posts.edit', compact('post', 'groupedRegions', 'statuses', 'realPosts'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        // Delete selected images
        if ($request->input('delete_images')) {
             foreach ($request->input('delete_images') as $imageId) {
                 $image = $post->images()->find($imageId);
                 if ($image) {
                     Storage::disk('public')->delete($image->original);
                     if (isset($image->sizes['medium'])) Storage::disk('public')->delete($image->sizes['medium']);
                     if (isset($image->sizes['thumbnail'])) Storage::disk('public')->delete($image->sizes['thumbnail']);
                     $image->delete();
                 }
             }
        }

        // Add new images with processing
        if ($request->hasFile('new_images')) {
            $manager = new ImageManager(new Driver());
             foreach ($request->file('new_images') as $imageFile) {
                 // تمرير المدير إلى الدالة المساعدة
                 $this->processAndStoreImage($imageFile, $post, $manager);
             }
        }

        // ... (video logic) ...

        return redirect()->route('editor.posts.index')
                         ->with('success', 'تم تعديل المنشور بنجاح.');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            // Delete all associated media files from storage
            foreach ($post->images as $image) {
                Storage::disk('public')->delete($image->original);
                if (isset($image->sizes['medium'])) Storage::disk('public')->delete($image->sizes['medium']);
                if (isset($image->sizes['thumbnail'])) Storage::disk('public')->delete($image->sizes['thumbnail']);
            }
             foreach ($post->videos as $video) {
                Storage::disk('public')->delete($video->video_url);
            }
            $post->delete();
            return redirect()->route('editor.posts.index')
                             ->with('success', 'تم حذف المنشور وجميع وسائطه المرتبطة بنجاح.');

        } catch (\Exception $e) {
             return redirect()->route('editor.posts.index')
                             ->with('error', 'حدث خطأ غير متوقع أثناء الحذف: ' . $e->getMessage());
        }
    }

    /**
     * Helper function to process and store an uploaded image using Intervention Image v3.
     *
     * @param \Illuminate\Http\UploadedFile $imageFile
     * @param \App\Models\Post $post
     * @param \Intervention\Image\ImageManager $manager  <-- تم تعديل استدعاء الدالة
     * @return void
     */
    private function processAndStoreImage($imageFile, Post $post, ImageManager $manager): void
    {
        $imageName = uniqid('post_') . '_' . time() . '.' . $imageFile->getClientOriginalExtension();
        $extension = 'webp'; // استخدام امتداد WebP المحسن
        $basePath = 'posts/images/';
    $originalPath = $basePath . 'original/' . $imageName;
        $mediumPath   = $basePath . 'medium/' . pathinfo($imageName, PATHINFO_FILENAME) . '.' . $extension;
        $thumbnailPath = $basePath . 'thumbnail/' . pathinfo($imageName, PATHINFO_FILENAME) . '.' . $extension;

        // --- New v3 Syntax ---

        // Medium size (max width 1200px, 80% quality)
        $mediumImage = $manager->read($imageFile)->resize(width: 1200);
        Storage::disk('public')->put($mediumPath, $mediumImage->toWebp(80));

        // Thumbnail size (fit to 400x240, 85% quality)
        $thumbnailImage = $manager->read($imageFile)->cover(400, 240);
        Storage::disk('public')->put($thumbnailPath, $thumbnailImage->toWebp(85));

        // Store the original file
    Storage::disk('public')->putFileAs($basePath . 'original', $imageFile, $imageName);

        // Create a record in the database
        $post->images()->create([
        'image_url' => $originalPath, // Use the old column name for the original
        'sizes' => [
            'medium' => $mediumPath,
            'thumbnail' => $thumbnailPath,
        ]
    ]);
    }
}