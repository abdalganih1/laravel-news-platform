<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // استيراد Auth facade

// --- Controllers ---
use App\Http\Controllers\ProfileController; // من Breeze
// استيراد متحكمات المدير والمحرر
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Editor\DashboardController as EditorDashboardController; // استيراد متحكم المحرر (إذا وجد)
// ... (use statements) ...
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\ClaimController as FrontendClaimController;
use App\Http\Controllers\Frontend\PageController;
use App\Models\Claim; // استيراد نموذج البلاغ
use App\Models\Favorite; // استيراد نموذج المفضلة
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Public Routes ---
Route::get('/', function () {
    // يمكن إعادة توجيه المستخدم المسجل مباشرة من هنا إذا أردت
    // if (Auth::check()) {
    //     return redirect('/dashboard');
    // }
    return view('welcome');
});

// ... (Dynamic Dashboard Route) ...
Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // Redirection logic for Admin/Editor dashboards remains the same
    if ($user && $user->user_role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user && $user->user_role === 'editor') {
        return redirect()->route('editor.dashboard');
    }

    // --- For Normal Users: Fetch Stats ---
    $userStats = [
        'myClaimsCount' => $user->claims()->count(), // Count claims submitted by this user
        'myFavoritesCount' => $user->favorites()->count(), // Count favorites for this user
    ];
    // -------------------------------------

    // Pass stats to the default dashboard view
    return view('dashboard', compact('userStats'));

})->middleware(['auth', 'verified'])->name('dashboard'); // Middleware and name remain

// --- Authenticated User Routes (Profile, etc.) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Claims routes
    Route::get('/claims/create', [FrontendClaimController::class, 'create'])->name('frontend.claims.create');
    Route::post('/claims', [FrontendClaimController::class, 'store'])->name('frontend.claims.store');
    Route::get('/my-claims', [FrontendClaimController::class, 'index'])->name('frontend.claims.index');



    // --- Favorites Routes ---
    Route::get('/my-favorites', [\App\Http\Controllers\Frontend\FavoriteController::class, 'index'])->name('frontend.favorites.index');
    // Routes to add/remove favorite (often done via AJAX/API, but defining web routes here)
    // These would typically be POST/DELETE requests
    Route::post('/posts/{post}/favorite', [\App\Http\Controllers\Frontend\FavoriteController::class, 'store'])->name('frontend.favorites.store');
    Route::delete('/posts/{post}/unfavorite', [\App\Http\Controllers\Frontend\FavoriteController::class, 'destroy'])->name('frontend.favorites.destroy');

    // --- Add route for requesting editor role ---
    Route::post('/profile/request-editor', [\App\Http\Controllers\ProfileController::class, 'requestEditorRole'])->name('profile.requestEditorRole');
    
    // أضف هنا مسارات أخرى خاصة بالمستخدم المسجل (مثل المفضلة، بلاغاتي)
    // Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.myIndex');
    // Route::get('/my-claims', [ClaimController::class, 'index'])->name('claims.myIndex');
});
Route::get('/posts/{post}/favorite-status', [\App\Http\Controllers\Frontend\FavoriteController::class, 'checkStatus'])->name('frontend.favorites.checkStatus')->middleware('auth'); // Require auth to check status

// --- ADMIN ROUTES ---
// هذه المجموعة محمية بواسطة middleware المصادقة والتحقق من دور 'admin'
// استخدم اسم الـ middleware الذي سجلته (عادة 'role')
// --- ADMIN ROUTES ---
// هذه المجموعة محمية بواسطة middleware المصادقة والتحقق من دور 'admin'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // لوحة تحكم المدير
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- المسارات المضافة ---

    // إدارة المستخدمين (CRUD كامل)
    // هذا السطر يوفر تلقائياً المسارات التالية بأسماء مناسبة:
    // admin.users.index, admin.users.create, admin.users.store, admin.users.show,
    // admin.users.edit, admin.users.update, admin.users.destroy
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // إدارة المحافظات (CRUD بدون صفحة عرض منفصلة)
    // يوفر: admin.governorates.index, create, store, edit, update, destroy
    Route::resource('governorates', \App\Http\Controllers\Admin\GovernorateController::class)->except(['show']);
    Route::post('governorates/{governorate}/regions', [\App\Http\Controllers\Admin\GovernorateController::class, 'storeRegion'])->name('governorates.regions.store');
    Route::put('regions/{region}', [\App\Http\Controllers\Admin\GovernorateController::class, 'updateRegion'])->name('regions.update');
    Route::delete('regions/{region}', [\App\Http\Controllers\Admin\GovernorateController::class, 'destroyRegion'])->name('regions.destroy');

    // إدارة معلومات الموقع (صفحة تعديل وتحديث فقط)
    Route::get('/site-info', [\App\Http\Controllers\Admin\SiteInfoController::class, 'edit'])->name('siteinfo.edit'); // لعرض نموذج التعديل
    Route::patch('/site-info', [\App\Http\Controllers\Admin\SiteInfoController::class, 'update'])->name('siteinfo.update'); // لحفظ التعديلات

    // ------------------------

});

// --- EDITOR ROUTES (Optional Example) ---
// هذه المجموعة محمية بواسطة middleware المصادقة ودور 'editor' (أو 'admin' يمكنه الوصول أيضاً)
Route::middleware(['auth', 'role:editor,admin'])->prefix('editor')->name('editor.')->group(function () {

    // ---- ADD THIS LINE ----
    Route::get('/dashboard', [EditorDashboardController::class, 'index'])->name('dashboard');
    // -----------------------

    // --- Ensure other editor routes exist as needed ---
    // Example: Manage Posts (assuming you created Editor\PostController with --resource)
    //  Route::resource('posts', \App\Http\Controllers\Editor\PostController::class);

    // Example: Review Claims (assuming you created Editor\ClaimController)
    Route::get('/claims', [\App\Http\Controllers\Editor\ClaimController::class, 'index'])->name('claims.index'); // List pending claims
    // Add routes for showing/updating claim status if needed (e.g., show, update/patch)
     Route::get('/claims/{claim}', [\App\Http\Controllers\Editor\ClaimController::class, 'show'])->name('claims.show'); // Show specific claim details
     Route::patch('/claims/{claim}/review', [\App\Http\Controllers\Editor\ClaimController::class, 'review'])->name('claims.review'); // Process claim review
    // تعديل مسار إنشاء المنشور
    Route::get('/posts/create/{claim?}', [\App\Http\Controllers\Editor\PostController::class, 'create'])->name('posts.create');
    Route::resource('posts', \App\Http\Controllers\Editor\PostController::class)->except(['create']); // استثناء 'create' لتجنب التعارض
    Route::patch('/claims/{claim}/cancel', [\App\Http\Controllers\Editor\ClaimController::class, 'cancel'])->name('claims.cancel');


});

// ... (other routes) ...

// --- Frontend Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/posts', [FrontendPostController::class, 'index'])->name('frontend.posts.index');
Route::get('/posts/{post}', [FrontendPostController::class, 'show'])->name('frontend.posts.show')->where('post', '[0-9]+');

Route::get('/about', [PageController::class, 'about'])->name('frontend.pages.about');
// ... add other static page routes here ...

// --- Frontend Authenticated Routes ---
Route::middleware('auth')->group(function () {
    // ... (profile routes from Breeze) ...

    Route::get('/claims/create', [FrontendClaimController::class, 'create'])->name('frontend.claims.create');
    Route::post('/claims', [FrontendClaimController::class, 'store'])->name('frontend.claims.store');
    Route::get('/my-claims', [FrontendClaimController::class, 'index'])->name('frontend.claims.index');

    // Route for favorites (could be API or web)
    // Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('frontend.favorites.index');
});

// --- Breeze Authentication Routes ---
// هذا السطر يقوم بتضمين المسارات الخاصة بالمصادقة (تسجيل الدخول، التسجيل، ...)
require __DIR__.'/auth.php';