<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('raw_id')->unsigned();
            $table->double('quantity')->unsigned();
            $table->tinyInteger('type');
            $table->tinyInteger('from');
            $table->boolean('status');


            $table->timestamps();
           
            $table->foreign('raw_id')->references('id')
            ->on('raws')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
