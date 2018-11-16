<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWardsTable extends Migration
{
    public function up()
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->unsignedInteger('bishop_id')->nullable();
            $table->unsignedInteger('stake_id')->nullable();
            $table->timestamps();

            $table->foreign('bishop_id')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('stake_id')
                ->references('id')->on('stakes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wards');
    }
}
