<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenchmarksResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benchmarks_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('recording_date');
            $table->integer('repetition');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        
            $table->unsignedBigInteger('benchmark_id');
            $table->foreign('benchmark_id')
                ->references('id')
                ->on("benchmarks")
                ->onDelete('cascade');        
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
        Schema::dropIfExists('benchmarks_results');
    }
}
