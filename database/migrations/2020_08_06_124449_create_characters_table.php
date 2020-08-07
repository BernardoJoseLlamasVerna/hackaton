<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character', function (Blueprint $table) {
            $table->increments('characterId');
            //FK:skinId
            $table->integer('skinId')->unsigned();
            $table->foreign('skinId')->references('skinId')->on('skinColor')->onDelete('cascade')->onUpdate('cascade');
            //FK:skinId
            //FK:hairId
            $table->integer('hairId')->unsigned();
            $table->foreign('hairId')->references('hairId')->on('hairColor')->onDelete('cascade')->onUpdate('cascade');
            //FK:hairId
            $table->string('characterName');
            $table->integer('characterIdInSwapi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
