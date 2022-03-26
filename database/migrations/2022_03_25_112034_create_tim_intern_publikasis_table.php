<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimInternPublikasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tim_intern_publikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lap_publikasi_id');
            $table->char('nidn',10);
            $table->boolean('isLeader');
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
        Schema::dropIfExists('tim_intern_publikasis');
    }
}
