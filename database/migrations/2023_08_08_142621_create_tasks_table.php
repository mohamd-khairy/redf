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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->dateTime('due_date');
            $table->text('details')->nullable();
            $table->string('share_with')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assigner_id');
            $table->unsignedBigInteger('document_id')->nullable();
            $table->unsignedBigInteger('form_id')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
