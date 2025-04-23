<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('franchise_temps', function (Blueprint $table) {
        $table->id();
        $table->string('company_name');
        $table->string('name')->nullable();
        $table->string('email')->nullable();
        $table->string('mobile')->nullable();
        $table->string('alt_mobile')->nullable();
        $table->integer('employees')->nullable();
        $table->string('address')->nullable();
        $table->integer('pincode')->nullable();
        $table->string('city')->nullable();
        $table->string('state')->nullable();
        $table->string('country')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_temps');
    }
};
