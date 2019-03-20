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
        if(!Schema::hasTable('dollies')){
            Schema::create('dollies', function (Blueprint $table) {
                $table->bigInteger('user_id')->unsigned();
                $table->string('name');
                $table->string('description');
                $table->integer('amount');
                $table->string('currency');
                $table->foreign('user_id')->references('id')->on('users');
                $table->timestamps();
                $table->primary(['user_id', 'name']);
            });
        }
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
