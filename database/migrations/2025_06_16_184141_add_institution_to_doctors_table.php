<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('doctors', function (Blueprint $table) {
        $table->string('institution')->nullable()->after('spesialis'); // atau after kolom yang logis
    });
}


};
