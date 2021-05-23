<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmRawParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firm_raw_parent', function (Blueprint $table) {
            $table->integer('firm_id')->unsigned();
            $table->integer('raw_parent_id')->unsigned();
            $table->timestamps();


            $table->primary(['firm_id','raw_parent_id']);
            $table->foreign('firm_id')->references('id')
            ->on('firms')->onDelete('cascade');
            $table->foreign('raw_parent_id')->references('id')
            ->on('raw_parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm_raw_parent');
    }
}
