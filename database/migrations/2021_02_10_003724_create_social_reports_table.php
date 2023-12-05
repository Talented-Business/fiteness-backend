<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('content');
            $table->enum('type',['post','profile'])->default('post');
            $table->enum('status',['pending','completed','waiting'])->default('pending');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('object_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_reports');
    }
}
