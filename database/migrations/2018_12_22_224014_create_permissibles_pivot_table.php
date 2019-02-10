<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissiblesPivotTable extends Migration
{
    public function up()
    {
        Schema::create('permissibles', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned()->index();
            $table->integer('permissible_id')->unsigned()->index();
            $table->string('permissible_type', 255);
            $table->unsignedInteger('team_id')->nullable()->index();

            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::drop('permissibles');
    }
}
