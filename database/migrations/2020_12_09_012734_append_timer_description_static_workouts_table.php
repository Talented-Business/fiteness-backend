<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendTimerDescriptionStaticWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_workouts', function (Blueprint $table) {
            $table->string('calentamiento_timer_description')->nullable();
            $table->string('con_content_timer_description')->nullable();
            $table->string('sin_content_timer_description')->nullable();
            $table->string('strong_male_timer_description')->nullable();
            $table->string('strong_female_timer_description')->nullable();
            $table->string('fit_timer_description')->nullable();
            $table->string('cardio_timer_description')->nullable();
            $table->string('extra_sin_timer_description')->nullable();
            $table->string('activo_timer_description')->nullable();
            $table->string('blog_timer_description')->nullable();       
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('static_workouts', function (Blueprint $table) {
            $table->dropColumn('calentamiento_timer_description');
            $table->dropColumn('con_content_timer_description');
            $table->dropColumn('sin_content_timer_description');
            $table->dropColumn('strong_male_timer_description');
            $table->dropColumn('strong_female_timer_description');
            $table->dropColumn('fit_timer_description');
            $table->dropColumn('cardio_timer_description');
            $table->dropColumn('extra_sin_timer_description');
            $table->dropColumn('activo_timer_description');
            $table->dropColumn('blog_timer_description');
        });
    }
}
