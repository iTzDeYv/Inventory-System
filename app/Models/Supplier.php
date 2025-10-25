<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
   // optional but recommended

    protected $fillable = ['supplier_name', 'supplier_contact', 'delivery_date'];

    protected $casts = [
        'delivery_date' => 'date',
    ];
}
