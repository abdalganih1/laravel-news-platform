<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->loadMissing('governorate'); // تحميل علاقة المحافظة

        // يمكنك جلب المحافظات هنا إذا كنت تسمح بتعديلها من صفحة البروفايل
        // $governorates = \App\Models\Governorate::orderBy('name')->get();
        // return view('profile.edit', ['user' => $user, 'governorates' => $governorates]);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information, including image.
     */
   // app/Http/Controllers/ProfileController.php

public function update(ProfileUpdateRequest $request): RedirectResponse
{
    // الحصول على البيانات المصدقة أولاً
    $user = $request->user();
    $user->fill($request->validated());

    // التحقق من تغيير البريد الإلكتروني
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // --- منطق الصورة (يتم تنفيذه بشكل منفصل ومباشر) ---
    if ($request->hasFile('profile_image')) {
        // حذف الصورة القديمة إذا كانت موجودة
        if ($user->profile_image_path) {
            Storage::disk('public')->delete($user->profile_image_path);
        }

        // تخزين الصورة الجديدة
        $path = $request->file('profile_image')->store('profile_images', 'public');

        // *** تحديث المسار مباشرة على كائن المستخدم ***
        $user->profile_image_path = $path;
    }
    // ----------------------------------------------------

    // حفظ جميع التغييرات (النصية والصورة) في قاعدة البيانات
    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // --- حذف الصورة الشخصية من Storage عند حذف المستخدم ---
        if ($user->profile_image_path) {
            Storage::disk('public')->delete($user->profile_image_path);
        }
        // ---------------------------------------------------

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * (Optional) Route to remove profile image specifically.
     * You would need to define a route for this (e.g., DELETE /profile/image).
     */
    /*
    public function removeImage(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_image_path) {
            Storage::disk('public')->delete($user->profile_image_path);
            $user->profile_image_path = null;
            $user->save();
            return Redirect::route('profile.edit')->with('status', 'profile-image-removed');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-no-image');
    }
    */
      /**
     * Handle the request from a normal user to become an editor.
     */
    public function requestEditorRole(): RedirectResponse
    {
        /** @var \App\Models\User $user */ // تلميح لنوع المتغير
        $user = Auth::user();

        // التأكد من أن المستخدم هو 'normal' حالياً
        if ($user->user_role !== 'normal') {
             return Redirect::route('dashboard')->with('warning', 'لا يمكنك إرسال هذا الطلب حالياً.');
        }

        // التحقق إذا كان قد أرسل طلباً سابقاً (حالة 'pending_editor')
         if ($user->isRequestingEditor()) { // استخدام الدالة المساعدة في النموذج
             return Redirect::route('dashboard')->with('warning', 'لقد قمت بتقديم طلب بالفعل وهو قيد المراجعة.');
         }

        // تغيير دور المستخدم إلى حالة 'pending_editor'
        $user->user_role = 'pending_editor';
        $user->save();

        // يمكنك إرسال إشعار إلى المدراء هنا لإعلامهم بالطلب الجديد

        return Redirect::route('dashboard')->with('success', 'تم إرسال طلبك ليصبح محررًا بنجاح. سيتم مراجعته من قبل الإدارة.');
    }
    
}