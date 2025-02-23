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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vendor_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('brand')->nullable();
            $table->float('price');
            $table->float('stock_quantity')->nullable();
            $table->json('specifications')->nullable();
            $table->string('sizes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
