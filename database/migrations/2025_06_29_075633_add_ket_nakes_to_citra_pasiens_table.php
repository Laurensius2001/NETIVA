<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('citra_pasiens', function (Blueprint $table) {
        $table->text('ket_nakes')->nullable();
    });
}

public function down()
{
    Schema::table('citra_pasiens', function (Blueprint $table) {
        $table->dropColumn('ket_nakes');
    });
}

};
