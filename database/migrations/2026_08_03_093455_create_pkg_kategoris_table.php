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
        Schema::create('pkg_kategoris', function (Blueprint $table) {
            $table->id();
            $table->integer('aspek'); // 1-7
            $table->string('nama'); // e.g. "Tujuan Pembelajaran"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkg_kategoris');
    }
};
