<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE clinics MODIFY plan VARCHAR(30) NOT NULL DEFAULT 'free'");

            return;
        }

        // SQLite (used by the test suite) has no MODIFY.
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('plan', 30)->default('free')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE clinics MODIFY plan ENUM('free', 'basic', 'premium', 'deluxe') NOT NULL DEFAULT 'free'");
        }
    }
};
