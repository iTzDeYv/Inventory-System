<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_description')) {
                $table->text('product_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'product_quantity')) {
                $table->integer('product_quantity')->default(0);
            }
            if (!Schema::hasColumn('products', 'product_price')) {
                $table->decimal('product_price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('products', 'barcode_id')) {
                $table->string('barcode_id')->nullable();
            }
            if (!Schema::hasColumn('products', 'supplier_name')) {
                $table->string('supplier_name')->nullable();
            }
            if (!Schema::hasColumn('products', 'product_image')) {
                $table->string('product_image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_description',
                'product_quantity',
                'product_price',
                'barcode_id',
                'supplier_name',
                'product_image'
            ]);
        });
    }
};
