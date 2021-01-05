<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('coupon_id');
            $table->string('email');
            $table->enum('used',['yes','no'])->default('no');
            $table->unsignedBigInteger('customer_id');
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
        Schema::dropIfExists('coupon_emails');
    }
}
