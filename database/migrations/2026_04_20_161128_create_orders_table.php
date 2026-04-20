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
            $table->string('order_code', 16)->unique();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');
            $table->string('item_description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
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
