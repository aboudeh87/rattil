<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verses', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('sura_id', false, true);
            $table->tinyInteger('number', false, true);
            $table->tinyInteger('chapter', false, true);
            $table->integer('page', false, true);
            $table->text('characters');
            $table->text('text');
            $table->text('clean_text');
            $table->timestamps();

            $table->foreign('sura_id')->references('id')->on('suwar')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verses');
    }
}
