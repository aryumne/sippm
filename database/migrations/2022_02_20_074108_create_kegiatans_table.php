<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kegiatan');
            $table->boolean('jenis_kegiatan');
            $table->integer('jumlah_dana');
            $table->timestamp('tanggal_kegiatan')->nullable();
            $table->text('path_kegiatan');
            $table->foreignId('prodi_id');
            $table->foreignId('sumber_id');
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
        Schema::dropIfExists('kegiatans');
    }
}
