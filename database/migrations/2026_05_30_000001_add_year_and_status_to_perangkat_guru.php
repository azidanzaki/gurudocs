<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('perangkat_gurus', function (Blueprint $table) {
            $table->year('tahun')->nullable()->after('updated_at');
            $table->boolean('is_completed')->default(false)->after('tahun');
        });
    }

    public function down()
    {
        Schema::table('perangkat_gurus', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'is_completed']);
        });
    }
};
?>
