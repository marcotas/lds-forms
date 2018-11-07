<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStakesTable extends Migration
{
    public function up()
    {
        Schema::create('stakes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 256);
            $table->unsignedInteger('president_id')->nullable();
            $table->timestamps();

            $table->foreign('president_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stakes');
    }
}
