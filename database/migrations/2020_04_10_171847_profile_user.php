<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProfileUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_user');
            $table->string('profile_name');
            $table->string('tagline');
            $table->string('description');
            $table->string('public_email');
            $table->string('public_website')->nullable();
            $table->integer('id_country');
            $table->integer('id_city');
            $table->date('birthday');
            $table->string('occupation');
            $table->integer('id_status');
            $table->string('birthplace');
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
        Schema::dropIfExists('profile_user');
    }
}
