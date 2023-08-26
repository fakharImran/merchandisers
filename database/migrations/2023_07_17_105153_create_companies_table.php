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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->String('company');
            $table->bigInteger('code')->nullable()->default(12);
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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['company_id']); // Drop foreign key constraint
        });
        Schema::dropIfExists('stores');

        Schema::table('company_users', function (Blueprint $table) {
            $table->dropForeign(['company_id']); // Drop foreign key constraint
        });
        Schema::dropIfExists('company_users');

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['company_id']); // Drop foreign key constraint
        });
        Schema::dropIfExists('products');

        Schema::dropIfExists('companies');
    }
};
