<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendDumbellWeightCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->float('dumbells_weight')->nullable();//media
            $table->unsignedBigInteger('first_order_id')->default(0);
            $table->unsignedBigInteger('second_order_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('dumbells_weight');
            $table->dropColumn('first_order_id');
            $table->dropColumn('second_order_id');
        });
    }
}
