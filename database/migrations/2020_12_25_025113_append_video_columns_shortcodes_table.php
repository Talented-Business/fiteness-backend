<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendVideoColumnsShortcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            $table->integer('time')->nullable();
            $table->integer('level')->nullable();
            $table->unsignedBigInteger('alternate_a')->nullable();
            $table->decimal('multipler_a')->nullable();
            $table->unsignedBigInteger('alternate_b')->nullable();
            $table->decimal('multipler_b')->nullable();
            $table->text('instruction')->nullable();
            $table->string('video_url')->nullable();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shortcodes', function (Blueprint $table) {
            $table->dropColumn('time');
            $table->dropColumn('level');
            $table->dropColumn('alternate_a');
            $table->dropColumn('multipler_a');
            $table->dropColumn('alternate_b');
            $table->dropColumn('multipler_b');
            $table->dropColumn('instruction');
            $table->dropColumn('video_url');
        });
    }
}
