<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE clinics MODIFY plan VARCHAR(30) NOT NULL DEFAULT 'free'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE clinics MODIFY plan ENUM('free', 'basic', 'premium', 'deluxe') NOT NULL DEFAULT 'free'");
    }
};
