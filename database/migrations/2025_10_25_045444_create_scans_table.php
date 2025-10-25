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
    Schema::create('scans', function (Blueprint $table) {
        $table->id();
        $table->string('barcode_id');
        $table->string('product_name');
        $table->string('product_description')->nullable();
        $table->string('product_image')->nullable();
        $table->decimal('product_price', 10, 2);
        $table->string('supplier_name')->nullable();
        $table->integer('quantity')->default(1);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scans');
    }
};
