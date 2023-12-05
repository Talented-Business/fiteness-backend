<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['image','video', 'file'])->default('image');
            $table->string('mime_type');
            $table->string('alt_text')->nullable();
            $table->unsignedBigInteger('thumbnail_id')->nullable();
            $table->foreign('thumbnail_id')->references('id')->on('medias');
            $table->string('src');
            $table->string('url')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->unsignedBigInteger('post_id')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->enum('attachment', ['post','event','other'])->default('post');
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
        Schema::dropIfExists('medias');
    }
}
