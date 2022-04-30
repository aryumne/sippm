<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLapTtgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lap_ttgs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->year('tahun_perolehan');
            $table->year('tahun_penerapan')->nullable();
            $table->text('path_ttg');
            $table->text('path_bukti_sertifikat')->nullable();
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
        Schema::dropIfExists('lap_ttgs');
    }
}
