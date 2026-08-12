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
        $activeYear = \App\Models\TahunAjaran::where('is_active', true)->first()->nama ?? '2025/2026';

        Schema::table('mapels', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable();
        });
        \Illuminate\Support\Facades\DB::table('mapels')->update(['tahun_ajaran' => $activeYear]);
        Schema::table('mapels', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable(false)->change();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable();
        });
        \Illuminate\Support\Facades\DB::table('kelas')->update(['tahun_ajaran' => $activeYear]);
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable(false)->change();
        });

        Schema::table('guru_mapel_kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable();
        });
        \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->update(['tahun_ajaran' => $activeYear]);
        Schema::table('guru_mapel_kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
        Schema::table('guru_mapel_kelas', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran');
        });
    }
};
