<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->text('judul_jurnal');
            $table->text('nama_artikel');
            $table->string('jenis_jurnal');
            $table->text('path_jurnal');
            $table->date('tanggal_upload');
            $table->foreignId('proposal_id');
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
        Schema::dropIfExists('publikasis');
    }
}
