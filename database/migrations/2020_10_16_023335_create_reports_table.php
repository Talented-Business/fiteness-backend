<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('created_date');
            $table->integer('registered');
            $table->integer('inactive');
            $table->integer('guest');
            $table->integer('ex_customer');
            $table->integer('ex_trial');
            $table->integer('total_users');
            $table->integer('active_users');
            $table->integer('active_customers');
            $table->integer('active_trials');
            $table->integer('leaving_users');
            $table->integer('leaving_customers');
            $table->integer('leaving_trials');
            $table->integer('total_customers');
            $table->decimal('total_sales',8,2);  
            $table->integer('customer_base');
            $table->integer('customer_churn');
            $table->integer('trial_churn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
