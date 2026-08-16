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
        Schema::table('pkg_kategoris', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajarans')->onDelete('cascade')->after('aspek');
        });

        // Fetch active tahun ajaran or the first one if none is active
        $activeYear = \Illuminate\Support\Facades\DB::table('tahun_ajarans')->where('is_active', 1)->orderBy('id', 'desc')->first();
        if (!$activeYear) {
            $activeYear = \Illuminate\Support\Facades\DB::table('tahun_ajarans')->orderBy('id', 'desc')->first();
        }

        if ($activeYear) {
            \Illuminate\Support\Facades\DB::table('pkg_kategoris')->update(['tahun_ajaran_id' => $activeYear->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pkg_kategoris', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });
    }
};
