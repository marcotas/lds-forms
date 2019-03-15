<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpeechesTable extends Migration
{
    public function up()
    {
        Schema::create('speeches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('link', 255)->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->unsignedInteger('order')->nullable();
            $table->unsignedInteger('speaker_id')->nullable();
            $table->unsignedInteger('team_id');

            $table->date('date')->nullable();
            $table->dateTime('invited_at')->nullable();
            $table->unsignedInteger('invited_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->unsignedInteger('confirmed_by')->nullable();
            $table->timestamps();

            $table->foreign('speaker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('speeches');
    }
}
