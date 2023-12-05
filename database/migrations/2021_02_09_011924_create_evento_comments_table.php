<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->unsignedBigInteger('evento_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('level0');
            $table->integer('level1')->default(0);
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
        Schema::dropIfExists('evento_comments');
    }
}
