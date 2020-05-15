<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedInteger('benckmark_count')->nullable();
            $table->unsignedInteger('video_count')->nullable();
            $table->unsignedInteger('email_count')->nullable();
            $table->unsignedInteger('blog_count')->nullable();
            $table->unsignedInteger('session_count')->nullable();
            $table->unsignedInteger('edit_count')->nullable();
            $table->unsignedInteger('contact_count')->nullable();
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
        Schema::dropIfExists('records');
    }
}
