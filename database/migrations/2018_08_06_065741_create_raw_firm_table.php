<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawFirmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_firm', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('raw_id')->unsigned();
            $table->integer('firm_id')->unsigned();
            $table->integer('position_id')->unsigned()->nullable();
            $table->double('quantity')->unsigned()->default(0);
            $table->double('valid_quantity')->unsigned()->default(0);
            $table->tinyInteger('type');
            $table->timestamps();


            $table->foreign('raw_id')->references('id')
            ->on('raws')->onDelete('cascade');
            $table->foreign('firm_id')->references('id')
            ->on('firms')->onDelete('cascade');
            $table->foreign('position_id')->references('id')
            ->on('positions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_firm');
    }
}
