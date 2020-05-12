<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry', function (Blueprint $table) {
            $table->id('entry_id');
            $table->timestamps();
            $table->integer('version')->nullable();
            $table->string('episodes')->nullable();
            $table->boolean('nsfw')->default(false);
            $table->boolean('spoiler')->default(false);
            $table->boolean('sfx')->default(false);
            $table->string('notes')->nullable();

            $table->unsignedBigInteger('theme_id');
            $table->foreign('theme_id')->references('theme_id')->on('theme');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entry');
    }
}