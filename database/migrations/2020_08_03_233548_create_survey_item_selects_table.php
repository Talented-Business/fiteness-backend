<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveyItemSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_item_selects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_item_id');
            $table->foreign('survey_item_id')->references('id')->on('survey_items');
            $table->string('option_label');
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
        Schema::dropIfExists('survey_item_selects');
    }
}
