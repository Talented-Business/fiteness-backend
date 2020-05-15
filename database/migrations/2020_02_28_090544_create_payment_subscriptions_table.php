<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('provider',['nmi',  'paypal'])->default('paypal');
            $table->string('plan_id');
            $table->string('subscription_id');
            $table->unsignedBigInteger('customer_id');            
            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        
            $table->string('start_time');
            $table->datetime('start_date');
            $table->enum('transaction',['Changed', 'Done'])->default('Changed');
            $table->enum('status',['Active', 'Suspended','Cancelled','Expired','Approved','Approval_pending'])->default('Active');
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
        Schema::dropIfExists('payment_subscriptions');
    }
}
