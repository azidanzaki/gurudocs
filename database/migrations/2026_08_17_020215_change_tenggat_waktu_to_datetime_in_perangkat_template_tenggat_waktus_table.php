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
        Schema::table('perangkat_template_tenggat_waktus', function (Blueprint $table) {
            $table->dateTime('tenggat_waktu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perangkat_template_tenggat_waktus', function (Blueprint $table) {
            $table->date('tenggat_waktu')->change();
        });
    }
};
