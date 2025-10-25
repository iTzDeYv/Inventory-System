<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    // App\Models\Scan.php
protected $fillable = [
    'barcode_id',
    'product_name',
    'product_description',
    'product_price',
    'product_image',
    'supplier_name',
    'quantity',
];





}
