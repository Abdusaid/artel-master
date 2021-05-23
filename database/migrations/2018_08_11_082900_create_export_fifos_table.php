<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExportFifosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_fifos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('export_id')->unsigned();
            $table->foreign('export_id')->references('id')->on('exports')->onDelete('cascade');
            $table->string('serial_number');
            $table->double('quantity')->unsigned();
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
        Schema::dropIfExists('export_fifos');
    }
}
