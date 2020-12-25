<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendRemindersSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('reminder_before_seven')->nullable();
            $table->boolean('reminder_before_one')->nullable();
            $table->boolean('reminder_after_one')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('reminder_before_seven');
            $table->dropColumn('reminder_before_one');
            $table->dropColumn('reminder_after_one');
        });
    }
}
