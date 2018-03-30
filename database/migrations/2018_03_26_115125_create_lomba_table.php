<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLombaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lomba', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->text('poster_lomba');
            $table->string('nama_lomba');
            $table->string('jenis_lomba');
            $table->string('tgl_lomba');
            $table->date('waktu_lomba');
            $table->string('tempat_lomba');
            $table->enum('sifat_lomba', ['individu', 'tim']);
            $table->string('biaya_pendaftaran');
            $table->string('deskripsi_lomba');
            $table->text('file_legal');
            $table->rememberToken();
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
        //
        Schema::dropIfExists('users');

    }
}
