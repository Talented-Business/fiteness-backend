<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_item_id');
            $table->unsignedBigInteger('customer_id');
            $table->enum('question',['text','level','select'])->default('text');
            $table->string('text_answer')->nullable();
            $table->integer('level_answer')->nullable();
            $table->string('select_answer',1000)->nullable();
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
        Schema::dropIfExists('survey_reports');
    }
}
