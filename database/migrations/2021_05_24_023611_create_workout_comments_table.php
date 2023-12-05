<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkoutCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('publish_date');
            $table->enum('type',['basic', 'extra'])->default('basic');
            $table->text('content');
            $table->text('workout');
            $table->float('dumbells_weight')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();
            $table->unique(['publish_date', 'type', 'customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workout_comments');
    }
}
