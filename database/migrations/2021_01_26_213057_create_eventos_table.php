<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('done_date');
            $table->string('title');
            $table->string('description');
            $table->json('medias')->nullable();
            $table->decimal('latitude', 18, 14)->nullable();
            $table->decimal('longitude', 18, 14)->nullable();
            $table->string('address')->nullable();
            $table->enum('status',['Draft', 'Publish'])->default('Publish');
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
        Schema::dropIfExists('eventos');
    }
}
