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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->decimal('subtotal',10,2);
            $table->decimal('tax',10,2)->nullable();
            $table->decimal('shipping_cost', 10,2)->nullable();
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending');
            $table->string('pidx');
            $table->string('transcation_id')->nullable();
            $table->string('shipping_address');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
