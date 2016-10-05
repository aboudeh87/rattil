<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecitationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recitations', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->integer('sura_id', false, true);
            $table->integer('narration_id', false, true);
            $table->integer('from_verse', false, true);
            $table->integer('to_verse', false, true);
            $table->string('slug', 255)->unique();
            $table->text('description', 255)->nullable();
            $table->text('url', 255);
            $table->string('length', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('sura_id')->references('id')->on('suwar')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('from_verse')->references('id')->on('verses')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('to_verse')->references('id')->on('verses')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('narration_id')
                ->references('id')
                ->on('narrations')
                ->onUpdate('CASCADE')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recitations');
    }
}
