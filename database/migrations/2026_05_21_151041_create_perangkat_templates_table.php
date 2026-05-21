<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perangkat_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perangkat');       // e.g. "RPP", "Silabus"
            $table->text('deskripsi')->nullable();
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('perangkat_template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_template_id')
                  ->constrained()->cascadeOnDelete();
            $table->string('label');                // e.g. "Tujuan Pembelajaran"
            $table->string('field_key');            // e.g. "tujuan_pembelajaran"
            $table->enum('field_type', ['text', 'textarea', 'richtext', 'date', 'select'])
                  ->default('textarea');
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(true);
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('perangkat_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();
            $table->foreignId('perangkat_template_id')
                  ->constrained()->cascadeOnDelete();
            $table->string('tahun_ajaran');         // e.g. "2025/2026"
            $table->unsignedTinyInteger('semester');// 1 or 2
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])
                  ->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['user_id', 'mapel_id', 'kelas_id', 'perangkat_template_id', 'tahun_ajaran', 'semester'],
                'unique_perangkat_guru'
            );
        });

        Schema::create('perangkat_guru_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_guru_id')
                  ->constrained()->cascadeOnDelete();
            $table->foreignId('perangkat_template_section_id')
                  ->constrained()->cascadeOnDelete();
            $table->string('field_key');
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(
                ['perangkat_guru_id', 'perangkat_template_section_id'],
                'unique_guru_section'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perangkat_guru_sections');
        Schema::dropIfExists('perangkat_gurus');
        Schema::dropIfExists('perangkat_template_sections');
        Schema::dropIfExists('perangkat_templates');
    }
};