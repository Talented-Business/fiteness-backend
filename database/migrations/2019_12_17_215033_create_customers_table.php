<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender',['Male', 'Female'])->default('Male');
            $table->date('birthday');
            $table->string('register_ip');
            $table->string('country')->nullable();
            $table->string('country_code')->default('pa');
            $table->string('timezone')->nullable();
            $table->unsignedInteger('coupon_id')->nullable();//whether registering with coupon
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('email')->unique();
            $table->boolean('active_email')->default(1);
            $table->string('whatsapp_phone_number')->nullable();
            $table->boolean('active_whatsapp')->default(0);
            $table->enum('training_place',['Casa o Exterior', 'GYM', 'Ambos'])->default('Casa o Exterior');
            $table->decimal('initial_height',8,2);
            $table->enum('initial_height_unit',['cm', 'in'])->default('in');
            $table->decimal('initial_weight',8,2);
            $table->enum('initial_weight_unit',['kg', 'lbs'])->default('kg');
            $table->unsignedInteger('initial_condition');
            $table->foreign('initial_condition')
                ->references('id')
                ->on('conditions')
                ->onDelete('cascade');
            $table->decimal('current_height',8,2);
            $table->enum('current_height_unit',['cm', 'in'])->default('cm');
            $table->decimal('current_weight',8,2)->nullable();
            $table->enum('current_weight_unit',['kg', 'lbs'])->default('kg');
            $table->unsignedInteger('current_condition')->nullable();
            $table->foreign('current_condition')
                ->references('id')
                ->on('conditions')
                ->onDelete('cascade');
            $table->date('first_payment_date')->nullable();
            $table->boolean('email_update')->default(0);  
            $table->string('nmi_vault_id')->nullable();
            $table->enum('objective',['auto','strong', 'fit', 'cardio'])->default('auto');
            $table->enum('weights',['con pesas', 'sin pesas'])->default('sin pesas');
            //$table->enum('status',['Active', 'Free', 'Inactive', 'Disabled'])->default('Free');
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
        Schema::dropIfExists('customers');
    }
}
