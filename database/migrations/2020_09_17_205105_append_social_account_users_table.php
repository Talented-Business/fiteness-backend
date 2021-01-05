<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AppendSocialAccountUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_provider_id')->nullable();
            $table->string('facebook_name')->nullable();
            $table->string('google_provider_id')->nullable();
            $table->string('google_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('facebook_provider_id');
            $table->dropColumn('facebook_name');
            $table->dropColumn('google_provider_id');
            $table->dropColumn('google_name');
        });
    }
}
