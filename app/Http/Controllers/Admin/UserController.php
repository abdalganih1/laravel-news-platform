<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Governorate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\StoreUserRequest; // قد تحتاج لتحديثه للسماح بـ 'pending_editor'
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Validation\Rule; // استيراد Rule

class UserController extends Controller
{
    // إضافة 'pending_editor' إلى الأدوار المسموحة في لوحة التحكم
    private array $allowedRoles = ['admin', 'editor', 'normal', 'pending_editor'];
     // الأدوار التي يمكن للمدير تعيينها (لا يمكن تعيين pending_editor يدوياً)
    private array $assignableRoles = ['admin', 'editor', 'normal'];


    /**
     * عرض قائمة بجميع المستخدمين مع فلترة حسب الدور.
     */
    public function index(Request $request): View
    {
        $roleFilter = $request->input('role_filter'); // فلتر الدور من الرابط

        $query = User::with('governorate')
                     ->orderBy('last_name')
                     ->orderBy('first_name');

        // تطبيق الفلتر حسب الدور إذا تم تحديده
        if ($roleFilter && in_array($roleFilter, $this->allowedRoles)) {
            $query->where('user_role', $roleFilter);
        } else {
             // في العرض الافتراضي، قد لا تريد عرض طلبات pending_editor هنا
             // أو قد تريد عرض كل الأدوار باستثناء admin (للحماية)
             // هنا سنعرض الكل باستثناء pending_editor إذا لم يتم طلبها صراحةً
             // $query->whereNotIn('user_role', ['pending_editor']);
        }


        $users = $query->paginate(15);
        $availableRoles = $this->allowedRoles; // تمرير الأدوار المتاحة للفلتر في الواجهة (اختياري)

        return view('admin.users.index', compact('users', 'availableRoles', 'roleFilter'));
    }

    /**
     * Show the form for creating a new resource.
     * Roles list should exclude 'pending_editor'.
     */
    public function create(): View
    {
        $governorates = Governorate::orderBy('name')->get(['governorate_id', 'name']);
        // استخدم فقط الأدوار التي يمكن تعيينها يدوياً عند الإنشاء
        $roles = $this->assignableRoles;
        return view('admin.users.create', compact('governorates', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     * Ensure 'pending_editor' cannot be set directly here.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
         // تأكد أن StoreUserRequest تمنع 'pending_editor'
        $validatedData = $request->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        // معالجة الصورة الشخصية إذا تم رفعها (مشابه لمنطق ProfileController)
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validatedData['profile_image_path'] = $path;
        }


        User::create($validatedData);

        return redirect()->route('admin.users.index')
                         ->with('success', 'تمت إضافة المستخدم بنجاح.');
    }

     /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
         // تحميل علاقة المحافظة لصورة البروفايل
        $user->loadMissing(['governorate', 'posts']); // قد تحتاج علاقات أخرى للعرض
        return view('admin.users.show', compact('user'));
    }


    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $governorates = Governorate::orderBy('name')->get(['governorate_id', 'name']);
        // استخدم فقط الأدوار التي يمكن تعيينها يدوياً عند التعديل
        $roles = $this->assignableRoles;
        // إذا كان المستخدم هو 'pending_editor' أو 'rejected_editor' في المستقبل، يمكنك إظهار هذه الخيارات أيضًا للمدير
        // إذا كان دور المستخدم الحالي ليس ضمن الأدوار القابلة للتعيين، أضف دوره الحالي إلى القائمة
        if (!in_array($user->user_role, $this->assignableRoles)) {
            $roles[] = $user->user_role;
        }

        return view('admin.users.edit', compact('user', 'governorates', 'roles'));
    }


    /**
     * Update the specified user in storage, including role change.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // احصل على البيانات المصدقة
        $validatedData = $request->validated();

        // منع المدير من تغيير دوره أو دور مدير آخر من هنا
        if ($user->user_role === 'admin' && Auth::id() !== $user->user_id && $validatedData['user_role'] !== 'admin') {
             return redirect()->back()->with('error', 'لا يمكنك تغيير دور مدير نظام آخر مباشرة.');
        }
        if ($user->user_id === Auth::id() && $validatedData['user_role'] !== 'admin') {
             return redirect()->back()->with('error', 'لا يمكنك تغيير دورك الخاص من هنا.');
        }

        // تحديث كلمة المرور فقط إذا تم إدخال كلمة مرور جديدة
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // معالجة الصورة الشخصية (مشابه لمنطق ProfileController)
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validatedData['profile_image_path'] = $path;
        } elseif ($request->boolean('remove_profile_image')) { // إذا أضفت checkbox للحذف
             if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
                $validatedData['profile_image_path'] = null;
            }
        } else {
             // إذا لم يتم رفع صورة جديدة أو حذف صورة، احتفظ بالمسار الحالي
             unset($validatedData['profile_image_path']);
        }


        // إذا كان الدور يتغير من 'pending_editor' إلى 'editor' أو 'normal'، أزل حالة الطلب
        if ($user->user_role === 'pending_editor' && in_array($validatedData['user_role'], ['editor', 'normal'])) {
             // لا تحتاج لعمل شيء هنا، تغيير الدور سيغطي حالة الطلب
        }


        $user->update($validatedData); // تحديث المستخدم

        // يمكنك إضافة منطق لإرسال إشعار للمستخدم هنا إذا تم تغيير دوره (خاصة من pending_editor)

        return redirect()->route('admin.users.index')
                         ->with('success', 'تم تعديل بيانات المستخدم بنجاح.');
    }

    /**
     * Remove the specified user from storage.
     * Ensure deletion doesn't break constraints or delete current admin.
     */
    public function destroy(User $user): RedirectResponse
    {
        // منع المدير من حذف حسابه الخاص
        if ($user->user_id === Auth::id()) {
            return redirect()->route('admin.users.index')
                             ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

         // يمكنك إضافة قيود هنا بناءً على الـ onDelete في الهجرات (restrict, set null)
         // مثلاً، إذا كان onDelete('restrict') للمنشورات:
         // if ($user->posts()->exists()) {
         //      return redirect()->route('admin.users.index')
         //                     ->with('error', 'لا يمكن حذف المستخدم لوجود منشورات مرتبطة به.');
         // }


        try {
            // حذف الصورة الشخصية من Storage
            if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            $user->delete(); // حذف المستخدم (سيؤثر على العلاقات حسب الهجرات)

            return redirect()->route('admin.users.index')
                             ->with('success', 'تم حذف المستخدم بنجاح.');

        } catch (\Illuminate\Database\QueryException $e) {
             // التقاط خطأ قيود المفتاح الأجنبي (مثال)
            if ($e->getCode() === '23000') {
                 return redirect()->route('admin.users.index')
                                ->with('error', 'لا يمكن حذف المستخدم لوجود بيانات مرتبطة به (مثل بلاغات).');
            }
             return redirect()->route('admin.users.index')
                             ->with('error', 'حدث خطأ في قاعدة البيانات أثناء محاولة الحذف: ' . $e->getMessage());
        } catch (\Exception $e) {
             return redirect()->route('admin.users.index')
                             ->with('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }
}