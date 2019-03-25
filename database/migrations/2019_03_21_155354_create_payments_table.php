<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigInteger('dollie_id')->unsigned();
            $table->bigInteger('payer_id')->unsigned();
            $table->string('payment_id');
            $table->boolean('payed', false);
            $table->timestamps();
            $table->primary(['dollie_id', 'payer_id']);
            $table->foreign('dollie_id')->references('id')->on('dollies')->onDelete('cascade');
            $table->foreign('payer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
