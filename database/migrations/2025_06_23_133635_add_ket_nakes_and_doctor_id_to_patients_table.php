<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->text('ket_nakes')->nullable()->after('golongan_darah');
        $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('set null');
    });
}

public function down()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn(['ket_nakes', 'doctor_id']);
    });
}
};
