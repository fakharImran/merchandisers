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
        Schema::create('company_users', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('access_privilege');
            $table->string('last_login_date_time')->nullable();
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
            $table->dropForeign(['company_user_id']); // Drop foreign key constraint
            $table->dropForeign(['store_manager_id']); // Drop foreign key constraint
        });
    
        Schema::dropIfExists('merchandiser_time_sheets');
        Schema::dropIfExists('company_users');
    }
};