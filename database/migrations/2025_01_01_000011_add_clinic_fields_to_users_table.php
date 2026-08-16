<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('id')->constrained('clinics')->nullOnDelete();
            $table->enum('role', ['superadmin', 'admin', 'doctor', 'staff'])->default('doctor')->after('password');
            $table->string('color')->default('#1649FF')->after('role');
            $table->string('specialty')->nullable()->after('color');
            $table->boolean('is_active')->default(true)->after('specialty');
            $table->timestamp('last_login_at')->nullable()->after('is_active');

            $table->index('clinic_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['clinic_id']);
            $table->dropIndex(['clinic_id']);
            $table->dropColumn(['clinic_id', 'role', 'color', 'specialty', 'is_active', 'last_login_at']);
        });
    }
};
