<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PostGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_group');
            $table->integer('id_users');
            $table->integer('id_image_group')->nullable();
            $table->string('post');
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
        Schema::dropIfExists('post_groups');
    }
}
