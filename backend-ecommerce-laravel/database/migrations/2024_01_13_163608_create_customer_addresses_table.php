<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('customer_addresses', function (Blueprint $table) {
        $table->id();
        $table->string('type', 45);
        $table->string('address1', 255);
        $table->string('address2', 255);
        $table->string('city', 255);
        $table->string('state', 45)->nullable();
        $table->string('zipcode', 45);
        $table->string('country_code', 3);
        
        $table->unsignedBigInteger('customer_id'); // id user_id from customers table
        $table->timestamps();

        $table->foreign('customer_id')
              ->references('user_id')
              ->on('customers')
              ->onDelete('cascade');

        $table->foreign('country_code')
              ->references('code')
              ->on('countries');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
