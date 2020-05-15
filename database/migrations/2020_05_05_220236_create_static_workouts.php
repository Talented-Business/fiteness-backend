<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticWorkouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_workouts', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('from_date',['monday','tuesday','wednesday','thursday','friday','saturday','sunday'])->default('monday');
            $table->enum('weekdate',['monday','tuesday','wednesday','thursday','friday','saturday','sunday']);
            $table->text('comentario')->nullable();
            $table->text('calentamiento')->nullable();
            $table->text('sin_content')->nullable();
            $table->text('extra_sin')->nullable();
            $table->text('con_content')->nullable();
            $table->text('strong_male')->nullable();
            $table->text('strong_female')->nullable();
            $table->text('fit')->nullable();
            $table->text('cardio')->nullable();
            $table->text('activo')->nullable();
            $table->text('blog')->nullable();
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
        Schema::dropIfExists('static_workouts');
    }
}
