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
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('unique_id')->after('id')->nullable();
            $table->string('gst_no')->after('address')->nullable();
            $table->string('voucher_no')->after('gst_no')->nullable();
            $table->string('buyer_ref')->after('voucher_no')->nullable();
            $table->string('other_ref')->after('buyer_ref')->nullable();
            $table->string('dispatch')->after('other_ref')->nullable();
            $table->string('destination')->after('dispatch')->nullable();
            $table->string('city_port')->after('destination')->nullable();
            $table->string('terms_delivery')->after('city_port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            //
        });
    }
};
