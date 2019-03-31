<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDollieExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dollie_extras', function (Blueprint $table) {
            $table->bigInteger('dollie_id')->unsigned();
            $table->string('filename');
            $table->primary(['dollie_id']);
            $table->foreign('dollie_id')->references('id')->on('dollies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dollie_extras');
    }
}
