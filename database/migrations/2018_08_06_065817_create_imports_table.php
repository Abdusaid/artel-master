<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('raw_firm_id')->unsigned();
            $table->double('quantity')->unsigned();
            $table->boolean('new');
            $table->string('serial_number')->nullable();
            $table->string('supplier')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->string('container')->nullable();
            $table->string('comment')->nullable();

            $table->timestamps();

            $table->foreign('raw_firm_id')->references('id')
            ->on('raw_firm')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imports');
    }
}
