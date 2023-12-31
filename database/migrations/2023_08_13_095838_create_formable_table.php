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
        Schema::create('formable', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_request_id');
            $table->morphs('formable');
            $table->timestamps();
            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formable');
    }
};
