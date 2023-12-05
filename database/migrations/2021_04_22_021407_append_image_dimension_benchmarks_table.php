<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendImageDimensionBenchmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('benchmarks', function (Blueprint $table) {
            $table->float('image_width')->nullable();
            $table->float('image_height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benchmarks', function (Blueprint $table) {
            $table->dropColumn('image_width');
            $table->dropColumn('image_height');
        });
    }
}
