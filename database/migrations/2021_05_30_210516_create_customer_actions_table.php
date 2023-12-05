<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('admin_id')->nullable();//who
            $table->string('object_type')->nullable();//object such as customer or post or evento
            $table->unsignedBigInteger('object_id')->nullable();
            $table->enum('type', ['mute','unmute'])->default('mute');//action type
            $table->text('content')->nullable();
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
        Schema::dropIfExists('admin_actions');
    }
}
