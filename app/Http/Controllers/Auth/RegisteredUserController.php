<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
// --- استيراد Form Request الجديد ---
use App\Http\Requests\Auth\RegisterRequest;
// --- استيراد نموذج المحافظة لجلب القائمة (إذا أردت عرضها في نموذج التسجيل) ---
use App\Models\Governorate;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
         // جلب قائمة المحافظات إذا كنت ستعرضها في نموذج التسجيل
        $governorates = Governorate::orderBy('name')->get();
        return view('auth.register', compact('governorates'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // استخدم RegisterRequest الجديد للتحقق
    public function store(RegisterRequest $request): RedirectResponse
    {
        // البيانات تم التحقق منها بواسطة RegisterRequest
        $validatedData = $request->validated();

        // إنشاء المستخدم باستخدام البيانات المصدقة
        // حقل 'name' لم يعد موجوداً بعد الهجرة
        $user = User::create([
            'first_name' => $validatedData['first_name'], // استخدم الاسم الأول
            'last_name' => $validatedData['last_name'],   // استخدم الاسم الأخير
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['phone_number'] ?? null, // الحقول الاختيارية
            'governorate_id' => $validatedData['governorate_id'] ?? null,
            'user_role' => 'normal', // تعيين الدور الافتراضي 'normal' عند التسجيل

            // يمكنك إضافة أي حقول أخرى مطلوبة هنا
        ]);

        event(new Registered($user));

        Auth::login($user);

        // إعادة التوجيه إلى لوحة التحكم الافتراضية (ستقوم بإعادة التوجيه حسب الدور)
        return redirect(RouteServiceProvider::HOME);
    }
}