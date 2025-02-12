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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('brand_name')->nullable()->after('status'); 
            $table->string('product_image')->nullable()->after('brand_name'); 
            $table->string('mobile')->nullable()->after('zip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['brand_name', 'product_image', 'mobile']);
        });
    }
};
