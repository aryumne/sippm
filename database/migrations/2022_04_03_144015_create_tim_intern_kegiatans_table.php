<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimInternKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tim_intern_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id');
            $table->char('nidn', 10);
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
        Schema::dropIfExists('tim_intern_kegiatans');
    }
}
