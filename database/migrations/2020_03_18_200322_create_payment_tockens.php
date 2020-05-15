<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTockens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_tockens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('gateway',['nmi',  'paypal'])->default('nmi');
            $table->string('tocken');
            $table->unsignedBigInteger('customer_id');            
            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        
            $table->string('holder');
            $table->string('type');
            $table->string('last4');
            $table->integer('expiry_year');
            $table->string('expiry_month');
            $table->enum('mode',['sandbox',  'production'])->default('production');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_tockens');
    }
}
