<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * نقش‌ها و دسترسی‌های کاربران پنل مدیریت + گزارش فعالیت‌ها
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('is_super')->default(false);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('is_admin')->constrained('roles')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('role_id');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 30);
            $table->string('section', 60)->nullable();
            $table->string('subject')->nullable();
            $table->string('url')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });

        // مدیران فعلی، «مدیر کل» می‌شوند تا دسترسی کسی از بین نرود
        $superId = DB::table('roles')->insertGetId([
            'name' => 'مدیر کل',
            'description' => 'دسترسی کامل به همه بخش‌های پنل',
            'permissions' => json_encode([]),
            'is_super' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->where('is_admin', true)->update(['role_id' => $superId]);
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn(['is_active', 'last_login_at', 'last_login_ip']);
        });
        Schema::dropIfExists('roles');
    }
};
