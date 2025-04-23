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
        Schema::table('franchises', function (Blueprint $table) {
            $table->string('name')->nullable()->after('user_id'); 
            $table->string('email')->nullable()->after('name');
            $table->string('employees')->nullable()->after('email');
            $table->string('alt_mobile')->nullable()->after('mobile');
            $table->string('registerationType')->nullable()->after('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            //
        });
    }
};
