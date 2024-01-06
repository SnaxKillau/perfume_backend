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
        Schema::create('products_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id'); // Correct column name
            $table->unsignedBigInteger('user_id');    // Correct column name
            $table->timestamps();

            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_user');
    }
};
