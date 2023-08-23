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
        Schema::create('form_request_sides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_request_id');
            $table->unsignedBigInteger('claimant_id')->nullable();
            $table->unsignedBigInteger('defendant_id')->nullable();

            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');
            $table->foreign('claimant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('defendant_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('form_request_sides');
    }
};
