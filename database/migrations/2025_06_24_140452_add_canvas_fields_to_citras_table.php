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
        Schema::table('citra_pasiens', function (Blueprint $table) {
            $table->longText('canvas_sebelum')->nullable();
            $table->longText('canvas_sesudah')->nullable();
            $table->longText('canvas_ai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citra_pasiens', function (Blueprint $table) {
            $table->dropColumn('canvas_sebelum');
            $table->dropColumn('canvas_sesudah');
            $table->dropColumn('canvas_ai');
        });
    }
};
