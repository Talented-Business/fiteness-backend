<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransferRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transfer_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('plan_id');
            $table->unsignedInteger('coupon_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->decimal('total',8,2);    
            $table->datetime('done_date');
            $table->enum('frequency',['Mensual', 'Trimestral', 'Semestral', 'Anual'])->default('Mensual');
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
        Schema::dropIfExists('bank_transfer_requests');
    }
}
