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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('txn_id')->after('id')->nullable();
            $table->string('order_value')->after('quotation_id')->nullable();
            $table->string('payment_mode')->after('order_value')->nullable();
            $table->string('payment_mode_by')->after('payment_mode')->nullable();
            $table->string('paid_amount')->after('payment_mode_by')->nullable();
            $table->string('payment_type')->after('paid_amount')->nullable();
            $table->string('remarks')->after('payment_type')->nullable();
            $table->enum('status', [0, 1])
                ->comment('0 = "Pending", 1 = "Schedule", 2 = "Complete" ')->after('installation_date')->default(0);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
