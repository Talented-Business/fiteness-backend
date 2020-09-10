<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendBlogTimerColumnsStaticWorkoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_workouts', function (Blueprint $table) {
            $table->enum('blog_timer_type',['amrap','for_time','tabata','calentamiento','extra'])->nullable();
            $table->string('blog_timer_work')->nullable();
            $table->string('blog_timer_round')->nullable();
            $table->string('blog_timer_rest')->nullable();
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
            $table->dropColumn('blog_note');
            $table->dropColumn('blog_timer_type');
            $table->dropColumn('blog_timer_work');
            $table->dropColumn('blog_timer_round');
            $table->dropColumn('blog_timer_rest');
        });
    }
}
