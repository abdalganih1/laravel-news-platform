<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحقق أولاً من وجود العمود قبل محاولة حذفه
        if (Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // أعد إنشاء العمود (إذا لم تكن قد حذفته في الهجرة الأولى)
            // نوعه كان string وغير nullable بدون قيمة افتراضية
             $table->string('name')->nullable()->after('user_id'); // اجعله nullable في حالة الـ rollback لتجنب مشاكل مستقبلية
        });
    }
};