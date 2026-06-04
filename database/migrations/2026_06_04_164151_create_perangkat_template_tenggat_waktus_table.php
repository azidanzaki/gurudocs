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
        Schema::create('perangkat_template_tenggat_waktus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_template_id')->constrained()->cascadeOnDelete();
            $table->string('tahun_ajaran'); 
            $table->date('tenggat_waktu');
            $table->timestamps();

            $table->unique(['perangkat_template_id', 'tahun_ajaran'], 'unique_tenggat_per_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat_template_tenggat_waktus');
    }
};
