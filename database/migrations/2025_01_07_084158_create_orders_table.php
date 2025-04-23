<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // 'id' column (primary key, auto-incrementing)
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->unsignedBigInteger('franchise_id')->nullable();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->string('name');
            $table->string('mobile');
            $table->string('pincode');
            $table->date('installation_date')->nullable();
            $table->timestamps(); // 'created_at' and 'updated_at'
            
            // Optional: Foreign key constraints if required
            // $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            // $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('cascade');
            // $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
