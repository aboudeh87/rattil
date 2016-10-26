<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuwarContentTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suwar_content', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sura_id', false, true);
            $table->string('language_key', 2);
            $table->string('name', 255);
            $table->text('definition')->nullable();
            $table->timestamps();

            $table->unique(['sura_id', 'language_key']);
            $table->foreign('sura_id')->references('id')->on('suwar')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('suwar_content');
    }
}
