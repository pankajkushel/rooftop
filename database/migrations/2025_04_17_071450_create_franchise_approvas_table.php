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
        Schema::create('franchise_approvas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        $table->string('email')->unique();
        $table->string('mobile');
        $table->string('pincode');
        $table->string('city');
        $table->string('state');
        $table->string('country');
        $table->text('address');
        $table->string('registration_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_approvas');
    }
};
