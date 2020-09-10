<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendColumnsWorkouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->text('calentamiento_note')->nullable();
            $table->enum('calentamiento_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('calentamiento_timer_work')->nullable();
            $table->string('calentamiento_timer_round')->nullable();
            $table->string('calentamiento_timer_rest')->nullable();
            $table->text('con_content_note')->nullable(); 
            $table->enum('con_content_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('con_content_timer_work')->nullable();
            $table->string('con_content_timer_round')->nullable();
            $table->string('con_content_timer_rest')->nullable();
            $table->text('sin_content_note')->nullable(); 
            $table->enum('sin_content_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('sin_content_timer_work')->nullable();
            $table->string('sin_content_timer_round')->nullable();
            $table->string('sin_content_timer_rest')->nullable();
            $table->text('strong_male_note')->nullable(); 
            $table->enum('strong_male_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('strong_male_timer_work')->nullable();
            $table->string('strong_male_timer_round')->nullable();
            $table->string('strong_male_timer_rest')->nullable();
            $table->text('strong_female_note')->nullable(); 
            $table->enum('strong_female_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('strong_female_timer_work')->nullable();
            $table->string('strong_female_timer_round')->nullable();
            $table->string('strong_female_timer_rest')->nullable();
            $table->text('fit_note')->nullable(); 
            $table->enum('fit_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('fit_timer_work')->nullable();
            $table->string('fit_timer_round')->nullable();
            $table->string('fit_timer_rest')->nullable();
            $table->text('cardio_note')->nullable();
            $table->enum('cardio_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('cardio_timer_work')->nullable();
            $table->string('cardio_timer_round')->nullable();
            $table->string('cardio_timer_rest')->nullable();
            $table->text('extra_sin_note')->nullable(); 
            $table->enum('extra_sin_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('extra_sin_timer_work')->nullable();
            $table->string('extra_sin_timer_round')->nullable();
            $table->string('extra_sin_timer_rest')->nullable();
            $table->text('activo_note')->nullable();
            $table->enum('activo_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('activo_timer_work')->nullable();
            $table->string('activo_timer_round')->nullable();
            $table->string('activo_timer_rest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->dropColumn('calentamiento_note');
            $table->dropColumn('calentamiento_timer_type');
            $table->dropColumn('calentamiento_timer_work');
            $table->dropColumn('calentamiento_timer_round');
            $table->dropColumn('calentamiento_timer_rest');
            $table->dropColumn('con_content_note');
            $table->dropColumn('con_content_timer_type');
            $table->dropColumn('con_content_timer_work');
            $table->dropColumn('con_content_timer_round');
            $table->dropColumn('con_content_timer_rest');
            $table->dropColumn('sin_content_note');
            $table->dropColumn('sin_content_timer_type');
            $table->dropColumn('sin_content_timer_work');
            $table->dropColumn('sin_content_timer_round');
            $table->dropColumn('sin_content_timer_rest');
            $table->dropColumn('strong_male_note');
            $table->dropColumn('strong_male_timer_type');
            $table->dropColumn('strong_male_timer_work');
            $table->dropColumn('strong_male_timer_round');
            $table->dropColumn('strong_male_timer_rest');
            $table->dropColumn('strong_female_note');
            $table->dropColumn('strong_female_timer_type');
            $table->dropColumn('strong_female_timer_work');
            $table->dropColumn('strong_female_timer_round');
            $table->dropColumn('strong_female_timer_rest');
            $table->dropColumn('fit_note');
            $table->dropColumn('fit_timer_type');
            $table->dropColumn('fit_timer_work');
            $table->dropColumn('fit_timer_round');
            $table->dropColumn('fit_timer_rest');
            $table->dropColumn('cardio_note');
            $table->dropColumn('cardio_timer_type');
            $table->dropColumn('cardio_timer_work');
            $table->dropColumn('cardio_timer_round');
            $table->dropColumn('cardio_timer_rest');
            $table->dropColumn('extra_sin_note');
            $table->dropColumn('extra_sin_timer_type');
            $table->dropColumn('extra_sin_timer_work');
            $table->dropColumn('extra_sin_timer_round');
            $table->dropColumn('extra_sin_timer_rest');
            $table->dropColumn('activo_note');
            $table->dropColumn('activo_timer_type');
            $table->dropColumn('activo_timer_work');
            $table->dropColumn('activo_timer_round');
            $table->dropColumn('activo_timer_rest');
        });
    }
}
