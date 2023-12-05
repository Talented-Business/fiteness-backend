<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendImageDimensionProductGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_gallery', function (Blueprint $table) {
            $table->float('width')->nullable();
            $table->float('height')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_gallery', function (Blueprint $table) {
            $table->dropColumn('width');
            $table->dropColumn('height');
        });
    }
}
