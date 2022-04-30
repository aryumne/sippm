<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimExternNaskahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tim_extern_naskahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lap_naskah_id');
            $table->string('nama');
            $table->string('asal_institusi');
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
        Schema::dropIfExists('tim_extern_naskahs');
    }
}
