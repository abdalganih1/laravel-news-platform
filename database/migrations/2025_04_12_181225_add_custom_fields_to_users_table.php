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
        // *** استخدم Schema::table هنا ***
        Schema::table('users', function (Blueprint $table) {
            // افترض وجود عمود 'name' من Breeze وأضف الأعمدة الجديدة بعده أو بعد الأعمدة الأخرى
            $table->string('first_name', 100)->after('name')->nullable(); // أضف الاسم الأول بعد عمود 'name'
            $table->string('last_name', 100)->after('first_name')->nullable(); // أضف الاسم الأخير بعد الاسم الأول

            $table->string('phone_number', 25)->unique()->nullable()->after('email'); // أضف رقم الهاتف بعد الايميل
            $table->string('user_role', 20)->default('normal')->after('password'); // أضف دور المستخدم بعد كلمة المرور

            // أضف المفتاح الأجنبي للمحافظة
            $table->foreignId('governorate_id')->nullable()->after('user_role')
                  ->constrained('governorates', 'governorate_id') // يربط بـ governorates.governorate_id
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->date('date_of_birth')->nullable()->after('governorate_id'); // أضف تاريخ الميلاد
            $table->text('notes')->nullable()->after('date_of_birth'); // أضف الملاحظات

            // يمكنك إلغاء التعليق لحذف عمود 'name' إذا أردت استبداله بـ first_name و last_name
            // $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولاً
            // تأكد من اسم القيد إذا لم يكن الاسم الافتراضي يعمل
             // اسم القيد الافتراضي عادة هو: users_governorate_id_foreign
            try {
                 $table->dropForeign(['governorate_id']);
            } catch (\Exception $e) {
                 logger('Could not drop foreign key constraint for governorate_id: ' . $e->getMessage());
                 // يمكنك محاولة تحديد اسم القيد يدوياً إذا لزم الأمر
                 // $table->dropForeign('users_governorate_id_foreign');
            }


            // حذف الأعمدة المضافة
            $table->dropColumn([
                'notes',
                'date_of_birth',
                'governorate_id', // احذف العمود بعد حذف القيد
                'user_role',
                'phone_number',
                'last_name',
                'first_name',
            ]);

            // إذا قمت بحذف 'name' في دالة up()، أعد إضافته هنا
            // $table->string('name');
        });
    }
};