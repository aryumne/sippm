<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLapBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lap_bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('isbn')->nullable();
            $table->string('penerbit');
            $table->year('tahun');
            $table->text('path_buku');
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lap_bukus');
    }
}
