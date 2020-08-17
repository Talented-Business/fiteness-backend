<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('company_id');
            $table->enum('price_type',['offer','discounted'])->default('offer');
            $table->integer('discount')->nullable();
            $table->float('regular_price')->nullable();
            $table->float('price')->nullable();
            $table->string('description',2000);
            $table->enum('voucher_type',['once','unlimited'])->default('once');
            $table->date('expiration_date');
            $table->string('codigo')->nullable();
            $table->string('link')->nullable();
            $table->enum('status',['Active','Disabled'])->default('Active');
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
        Schema::dropIfExists('products');
    }
}
