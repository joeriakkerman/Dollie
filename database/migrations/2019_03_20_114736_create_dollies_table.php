<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDolliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dollies', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('name');
            $table->string('description');
            $table->integer('amount');
            $table->string('currency');
            $table->primary('user_id');
            $table->primary('name');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('dollies');
    }
}
