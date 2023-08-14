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
        Schema::create('form_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->date('date');
            $table->string('status');
            $table->text('details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key relationship with forms table
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_sessions');
    }
};
