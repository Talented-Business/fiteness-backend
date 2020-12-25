<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendAnalyseColumnsStaticWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_workouts', function (Blueprint $table) {
            $table->text('comentario_element')->nullable();
            $table->text('calentamiento_element')->nullable();
            $table->text('con_content_element')->nullable();
            $table->text('sin_content_element')->nullable();
            $table->text('strong_male_element')->nullable();
            $table->text('strong_female_element')->nullable();
            $table->text('fit_element')->nullable();
            $table->text('cardio_element')->nullable();
            $table->text('extra_sin_element')->nullable();
            $table->text('activo_element')->nullable();
            $table->text('blog_element')->nullable();
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
