<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('franchise_id')->nullable();
        $table->foreign('franchise_id')->references('id')->on('users')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['franchise_id']);
        $table->dropColumn('franchise_id');
    });
}

};
