<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_audits', function (Blueprint $table) {
            $table->id();
            $table->integer('perumusan');
            $table->integer('peluang');
            $table->integer('metode');
            $table->integer('tinjauan');
            $table->integer('kelayakan');
            $table->integer('total');
            $table->text('komentar');
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
        Schema::dropIfExists('hasil_audits');
    }
}
