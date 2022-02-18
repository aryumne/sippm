<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasilMonevsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasil_monevs', function (Blueprint $table) {
            $table->id();
            $table->json('luaran_wajib');
            $table->json('luaran_tambahan');
            $table->json('kesesuaian');
            $table->text('komentar');
            $table->foreignId('monev_id');
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
        Schema::dropIfExists('hasil_monevs');
    }
}
