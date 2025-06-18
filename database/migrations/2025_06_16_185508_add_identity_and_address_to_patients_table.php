<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('nik')->unique();
            $table->text('komentar')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('jalan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'komentar', 'provinsi', 'kota', 'kecamatan',
                'kelurahan', 'kode_pos', 'jalan'
            ]);
        });
    }

};
