<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_batch_id')->constrained()->cascadeOnDelete();
            $table->string('movement_type'); // IN, OUT, ADJUSTMENT
            $table->decimal('quantity', 12, 2); // positive = add to batch, negative = subtract
            $table->string('reference_type')->nullable(); // e.g. purchase_order, sales_order, adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
