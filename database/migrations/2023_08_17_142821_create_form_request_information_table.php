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
        Schema::create('form_request_information', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->decimal('percentage', 5, 2);
             $table->text('details');
            $table->unsignedBigInteger('form_request_id');
            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');
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
        Schema::dropIfExists('form_request_procedures');
    }
};
