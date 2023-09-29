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
        Schema::create('stock_count_by_stores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->unsignedBigInteger('company_user_id')->nullable();
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->string('category');
            $table->string('product_name');
            $table->bigInteger('product_number_sku');
            $table->bigInteger('stock_on_shelf');
            $table->bigInteger('stocks_packed');
            $table->bigInteger('stocks_in_storeroom');

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
        Schema::dropIfExists('stock_count_by_stores');
    }
};
