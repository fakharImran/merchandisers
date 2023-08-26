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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            // $table->String('company');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->String('name_of_store');
            $table->String('location');
            $table->String('parish');
            $table->String('channel')->nullable();
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
        Schema::table('merchandiser_time_sheets', function (Blueprint $table) {
            $table->dropForeign(['store_id']); // Drop foreign key constraint
        });
        Schema::dropIfExists('merchandiser_time_sheets');
        
        Schema::dropIfExists('stores');
    }
};
