<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryNameTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_name', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('country_id', false, true);
            $table->string('language_key', 2);
            $table->string('name');
            $table->timestamps();

            $table->foreign('language_key')->references('key')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_name');
    }
}
