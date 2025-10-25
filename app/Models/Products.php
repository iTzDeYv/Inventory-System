<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'product_image',
        'product_name',
        'product_description',
        'product_quantity',
        'product_price',
        'category_name',
        'supplier_name',
        'user_id', // new
        'barcode_id',
    ];

    protected static function booted()
    {
        static::deleting(function ($product) {
            if ($product->product_image && file_exists(public_path('db_img/' . $product->product_image))) {
                unlink(public_path('db_img/' . $product->product_image));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
