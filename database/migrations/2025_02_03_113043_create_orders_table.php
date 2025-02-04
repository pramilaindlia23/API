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
            
            $table->unsignedBigInteger('user_id')->nullable();  // Nullable for guest orders
            $table->string('name');
            $table->string('email');
            $table->text('address');
            $table->string('city');
            $table->string('zip');
            $table->string('discount_code')->nullable();
            $table->decimal('total', 8, 2);  // Total price
            $table->string('status')->default('pending'); 
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
