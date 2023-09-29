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
        Schema::create('planogram_compliance_trackers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->unsignedBigInteger('company_user_id')->nullable();
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->string('category')->nullable();
            $table->string('product_name')->nullable();
            $table->bigInteger('product_number_sku')->nullable();
            $table->string('pic_before_stocking_shelf')->nullable();
            $table->string('photo_after_stocking_shelf')->nullable();
            $table->string('action')->nullable();
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
        Schema::dropIfExists('planogram_compliance_trackers');
    }
};
