<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvitedAndConfirmedFieldsToTopicsTable extends Migration
{
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dateTime('invited_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn(['invited_at', 'confirmed_at']);
        });
    }
}
