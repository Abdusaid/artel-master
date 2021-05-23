<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFifosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fifos', function (Blueprint $table) {
            $table->integer('import_id')->unsigned();
            $table->double('quantity')->unsigned();
            $table->primary(['import_id']);

            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade');
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
        Schema::dropIfExists('fifos');
    }
}
