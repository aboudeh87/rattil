<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNarrationNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('narration_name', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('narration_id', false, true);
            $table->string('language_key', 2);
            $table->string('name');
            $table->timestamps();

            $table->foreign('narration_id')->references('id')->on('narrations')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('narration_name');
    }
}
