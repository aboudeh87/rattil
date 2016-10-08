<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReasonContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reason_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reason_id', false, true);
            $table->string('language_key', 2);
            $table->text('text')->nullable();
            $table->timestamps();

            $table->foreign('reason_id')->references('id')->on('reasons')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('reason_content');
    }
}
