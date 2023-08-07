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
        Schema::table('form_page_item_fills', function (Blueprint $table) {
            $table->unsignedBigInteger('form_request_id')->nullable();

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
        Schema::table('form_page_item_fills', function (Blueprint $table) {
            $table->dropColumn('form_request_id');
        });
    }
};
