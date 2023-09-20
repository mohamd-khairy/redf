<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('treatment_id');
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->text('text');
            $table->foreign('treatment_id')->references('id')->on('treatments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatment_actions');
    }
};
