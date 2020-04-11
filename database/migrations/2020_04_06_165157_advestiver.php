<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Advestiver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverstiver', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cost');
            $table->integer('type_addsense');
            $table->integer('id_image');
            $table->string('tag');
            $table->string('active')->nullable();
            $table->string('expired');
            $table->boolean('is_active');
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
        Schema::dropIfExists('adverstiver');
    }
}
