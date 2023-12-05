<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('follower_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('status',['muted','blocked'])->default('muted');
            $table->unique(['follower_id', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_relations');
    }
}
