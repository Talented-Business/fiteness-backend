<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->enum('bank_1',['yes', 'no'])->default('no');
            $table->enum('bank_3',['yes', 'no'])->default('no');
            $table->enum('bank_6',['yes', 'no'])->default('no');
            $table->enum('bank_12',['yes', 'no'])->default('no');
            $table->float('bank_fee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('bank_1');
            $table->dropColumn('bank_3');
            $table->dropColumn('bank_6');
            $table->dropColumn('bank_12');
            $table->dropColumn('bank_fee');
        });
    }
}
