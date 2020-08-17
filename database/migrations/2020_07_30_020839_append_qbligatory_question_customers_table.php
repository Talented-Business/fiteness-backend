<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendQbligatoryQuestionCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('qbligatory_question',['recommend','advertise','long'])->nullable();
            $table->unsignedBigInteger('friend_id')->nullable();
            $table->enum('friend',['yes','no'])->default('no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('qbligatory_question');
            $table->dropColumn('friend_id');
            $table->dropColumn('friend');
        });
    }
}
