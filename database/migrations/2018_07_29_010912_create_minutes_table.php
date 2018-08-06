<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinutesTable extends Migration
{
    public function up()
    {
        Schema::create('minutes', function (Blueprint $table) {
            $table->increments('id');

            // Required field
            $table->date('date');

            // Optional fields
            $table->string('presided_by')->nullable();
            $table->string('directed_by')->nullable();
            $table->string('receptionist')->nullable();
            $table->string('conductor')->nullable();
            $table->string('pianist')->nullable();
            $table->string('attendance')->nullable();
            $table->string('ward')->nullable();
            $table->string('stake')->nullable();

            $table->text('welcome')->nullable();
            $table->text('announcement')->nullable();
            $table->string('first_hymn')->nullable();
            $table->string('last_hymn')->nullable();
            $table->string('sacrament_hymn')->nullable();
            $table->string('intermediate_hymn')->nullable();
            $table->string('first_prayer')->nullable();
            $table->string('last_prayer')->nullable();
            $table->json('callings')->nullable();
            $table->json('confirmations')->nullable();
            $table->json('baby_blessings')->nullable();
            $table->json('ordinances')->nullable();
            $table->text('comments')->nullable();
            $table->string('first_speaker')->nullable();
            $table->string('second_speaker')->nullable();
            $table->string('third_speaker')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('minutes');
    }
}
