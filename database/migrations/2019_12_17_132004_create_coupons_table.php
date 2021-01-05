<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('mail')->nullable();
            $table->enum('type',['Public', 'Private', 'Referral','InvitationEmail','InvitationCode'])->default('Public');
            $table->unsignedBigInteger('customer_id')->nullable();
/*            $table->foreign('customer_id')
                ->references('id')
                ->on("customers")
                ->onDelete('cascade');        */
            $table->decimal('discount',8,2)->nullable();
            $table->enum('form',['%',  '$'])->default('%');
            $table->boolean('renewal')->default(0);//renewal 0=>no renewal 1=>yes
            $table->enum('status',['Active',  'Disabled'])->default('Active');
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
        Schema::dropIfExists('coupons');
    }
}
