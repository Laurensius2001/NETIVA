<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citra_pasiens', function (Blueprint $table) {
            $table->dropColumn(['canvas_sebelum', 'canvas_sesudah', 'canvas_ai']);
        });
    }

    public function down(): void
    {
        Schema::table('citra_pasiens', function (Blueprint $table) {
            $table->text('canvas_sebelum')->nullable();
            $table->text('canvas_sesudah')->nullable();
            $table->text('canvas_ai')->nullable();
        });
    }
};
