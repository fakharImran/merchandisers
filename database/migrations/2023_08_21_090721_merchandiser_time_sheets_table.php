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

            $table->string('gps_location');
            $table->string('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->string('store_manager_id');
            $table->foreign('store_manager_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->string('signature')->nullable();
            $table->string('signature_time')->nullable();
            $table->string('hours_worked')->nullable();
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
        Schema::table('time_sheet_records', function (Blueprint $table) {
            $table->dropForeign(['time_sheet_id']); // Drop foreign key constraint
        });
    
        Schema::dropIfExists('time_sheet_records');
        Schema::dropIfExists('merchandiser_time_sheets');
    }
};
