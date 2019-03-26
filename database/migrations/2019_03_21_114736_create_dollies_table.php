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
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->string('name');
                $table->string('description');
                $table->decimal('amount', 5, 2);
                $table->string('currency');
                $table->string('account_number');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('account_number')->references('account_number')->on('bankaccount');
                $table->timestamps();
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
