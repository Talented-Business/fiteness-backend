<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendCancelledQuestionsSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('quality_level')->nullable();
            $table->enum('cancelled_radio_reason',['good','not_suit','poor_quaulity','expensive','other'])->nullable();
            $table->string('cancelled_radio_reason_text',2000)->nullable();
            $table->string('recommendation',2000)->nullable();
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
            $table->dropColumn('quality_level');
            $table->dropColumn('cancelled_radio_reason');
            $table->dropColumn('cancelled_radio_reason_text');
            $table->dropColumn('recommendation');
        });
    }
}
