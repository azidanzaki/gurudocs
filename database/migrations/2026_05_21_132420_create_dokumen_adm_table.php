<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_adm', function (Blueprint $table) {

            $table->id();

            $table->string('judul');

            $table->string('jenis_dokumen');
            // RPP, Silabus, Modul Ajar dll

            $table->string('file_word')->nullable();
            $table->string('file_pdf')->nullable();
            // kalau admin upload file

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_adm');
    }
};