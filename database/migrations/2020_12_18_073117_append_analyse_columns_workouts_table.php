<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendAnalyseColumnsWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->string('comentario_element')->nullable();
            $table->string('calentamiento_element')->nullable();
            $table->string('con_content_element')->nullable();
            $table->string('sin_content_element')->nullable();
            $table->string('strong_male_element')->nullable();
            $table->string('strong_female_element')->nullable();
            $table->string('fit_element')->nullable();
            $table->string('cardio_element')->nullable();
            $table->string('extra_sin_element')->nullable();
            $table->string('activo_element')->nullable();
            $table->string('blog_element')->nullable();
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
            $table->dropColumn('comentario_element');
            $table->dropColumn('calentamiento_element');
            $table->dropColumn('con_content_element');
            $table->dropColumn('sin_content_element');
            $table->dropColumn('strong_male_element');
            $table->dropColumn('strong_female_element');
            $table->dropColumn('fit_element');
            $table->dropColumn('cardio_element');
            $table->dropColumn('extra_sin_element');
            $table->dropColumn('activo_element');
            $table->dropColumn('blog_element');
        });
    }
}
