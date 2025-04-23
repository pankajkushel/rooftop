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
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('franchise_id')->after('quotation_id')->nullable();
            $table->string('appointment_id')->after('franchise_id')->nullable();
            $table->tinyInteger('section_order')->after('appointment_id')->nullable();
            $table->tinyInteger('item_order')->after('section_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
           
        });
    }
};
