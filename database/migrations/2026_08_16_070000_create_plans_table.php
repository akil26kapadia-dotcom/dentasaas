<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('key', 30)->unique();
            $table->string('name', 60);
            $table->unsignedInteger('price_monthly')->default(0);
            $table->integer('patients_limit')->default(0);
            $table->integer('appointments_limit')->default(0);
            $table->integer('invoices_limit')->default(0);
            $table->integer('doctors_limit')->default(0);
            $table->boolean('pdf_export')->default(false);
            $table->boolean('prescriptions')->default(false);
            $table->string('analytics', 10)->default('none');
            $table->boolean('is_highlighted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
