<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('type');//0 nmi, 1 paypal, 2 bank
            $table->string('content');
            $table->unsignedBigInteger('customer_id')->nullable();            
            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        
            $table->unsignedInteger('plan_id')->nullable();//latest plan_id
            $table->foreign('plan_id')
                ->references('id')
                ->on("subscription_plans")
                ->onDelete('cascade');            
            $table->unsignedInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')
                ->references('id')
                ->on("coupons")
                ->onDelete('cascade');    
            $table->string('payment_transaction_id')->nullable();       
            $table->string('payment_subscription_id')->nullable();            
            $table->decimal('total',8,2);    
            $table->datetime('done_date');
            $table->enum('frequency',['Mensual', 'Trimestral', 'Semestral', 'Anual'])->default('Mensual');
            //$table->enum('status',['Pending','Processing', 'On-Hold', 'Completed', 'Refunded','Failed','Cancelled'])->default('Pending');
            $table->enum('status',['Pending','Completed','Declined'])->default('Pending');
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
        Schema::dropIfExists('transactions');
    }
}
