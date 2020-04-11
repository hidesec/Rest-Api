<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tutorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutorial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user');
            $table->string('link_video');
            $table->string('title');
            $table->string('description');
            $table->string('duration');
            $table->boolean('is_monetise');
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
        Schema::dropIfExists('tutorial');
    }
}
