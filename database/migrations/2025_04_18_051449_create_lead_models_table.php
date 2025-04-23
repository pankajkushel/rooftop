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
        Schema::create('lead_models', function (Blueprint $table) {
                $table->id();
                $table->string('full_name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('source')->nullable();
                // $table->string('status')->default('New');
                $table->unsignedBigInteger('assigned_to')->nullable(); // FK to users table
                $table->unsignedBigInteger('created_by');
                $table->string('status')->nullable()->default('pending');
                // $table->text('message')->nullable();
                // $table->string('interested_in')->nullable();
                // $table->integer('lead_score')->default(0);
                
                $table->string('address')->nullable();
                // $table->string('country')->default('India');
                // $table->string('state')->nullable();
                // $table->string('city')->nullable();
                
           
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_models');
    }
};
