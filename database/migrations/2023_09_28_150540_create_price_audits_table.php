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
        Schema::create('price_audits', function (Blueprint $table) {
            $table->id();
            $table->string('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->string('company_user_id')->nullable();
            $table->foreign('company_user_id')->references('id')->on('company_users')->onDelete('cascade');

            $table->string('category')->nullable();
            $table->string('product_name')->nullable();
            $table->bigInteger('product_number_sku')->nullable();
            $table->bigInteger('store_price')->nullable();
            $table->string('tax')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->string('compititor_product_name')->nullable();
            $table->bigInteger('compititor_product_price')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('price_audits');
    }
};
