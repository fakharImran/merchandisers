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
        Schema::create('merchandiser_time_sheets', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('company_user_id');
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');


            $table->string('gps_location')->nullable();
            $table->string('store_id')->nullable();
            $table->string('store_name')->nullable();
            $table->string('store_manager')->nullable();
            $table->string('store_location')->nullable();
            $table->string('status')->nullable();
            $table->string('date_time')->nullable();
            $table->string('merchandiser_name')->nullable();
            $table->string('merchandiser_id')->nullable();
            $table->string('signature')->nullable();
            $table->string('signature_time')->nullable();
            $table->string('hours_worked')->nullable();
            // $table->unsignedBigInteger('company_id');
            // $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('merchandiser_time_sheets');
    }
};
