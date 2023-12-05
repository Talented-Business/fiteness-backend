<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['payment_renewal','declined_payment', 'expiration','partners','social','events','follow','other'])->default('payment_renewal');
            $table->string('content');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('action_type')->nullable();//actor such as shop or user
            $table->unsignedBigInteger('action_id')->nullable();
            $table->string('object_type')->nullable();//object such as post or evento
            $table->unsignedBigInteger('object_id')->nullable();
            $table->string('route')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
