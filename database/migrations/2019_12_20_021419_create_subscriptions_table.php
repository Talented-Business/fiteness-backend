<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('plan_id');//latest plan_id
            $table->foreign('plan_id')
                ->references('id')
                ->on("subscription_plans")
                ->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');            
            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        
            $table->unsignedBigInteger('transaction_id')->nullable();//latest transaction
            $table->foreign('transaction_id')
                ->references('id')
                ->on("transactions")
                ->onDelete('cascade');        
            $table->unsignedInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')
                ->references('id')
                ->on("coupons")
                ->onDelete('cascade');            
            $table->enum('gateway',['paypal', 'nmi', 'bank'])->nullable();
            $table->string('payment_plan_id')->nullable();
            $table->datetime('start_date')->nullable();//latest start date
            $table->datetime('end_date')->nullable();//latest end date
            $table->string('meta')->nullable();
            $table->datetime('cancelled_date')->nullable();
            $table->text('cancelled_reason')->nullable();
            $table->enum('cancelled_now',['yes', 'no'])->nullable();
            $table->enum('frequency',['Mensual', 'Trimestral', 'Semestral', 'Anual','Once'])->default('Mensual');
            $table->enum('status',['Pending','Active','On-Hold','Pending-Cancellation', 'Cancelled','Expired'])->default('Active');

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
        Schema::dropIfExists('subscriptions');
    }
}
