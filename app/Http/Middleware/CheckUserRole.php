<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // استيراد Auth facade
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // قبول الأدوار المطلوبة كمصفوفة
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. تحقق أولاً إذا كان المستخدم مسجل الدخول
        if (!Auth::check()) {
            // إذا لم يكن مسجلاً، أعد توجيهه إلى صفحة تسجيل الدخول
            return redirect()->route('login');
        }

        // 2. الحصول على المستخدم الحالي
        /** @var \App\Models\User $user */ // تلميح لنوع المتغير
        $user = Auth::user();

        // 3. التحقق مما إذا كان المستخدم موجوداً بالفعل (احتياطي)
        //    والتحقق إذا كان دور المستخدم (user_role) موجوداً ضمن مصفوفة الأدوار المطلوبة ($roles)
        if (!$user || !in_array($user->user_role, $roles)) {
            // إذا لم يكن لدى المستخدم الدور المطلوب، أرجع خطأ 403 (Forbidden)
            // يمكنك تخصيص هذا السلوك (مثل إعادة التوجيه إلى لوحة التحكم الرئيسية برسالة خطأ)
            abort(403, 'Unauthorized action. You do not have the required role.');
            // مثال بديل: إعادة التوجيه
            // return redirect('/dashboard')->with('error', 'ليس لديك الصلاحية للوصول لهذه الصفحة.');
        }

        // 4. إذا كان المستخدم مسجلاً ولديه الدور المطلوب، اسمح للطلب بالمرور
        return $next($request);
    }
}