<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'name', 'sku', 'price', 'stock'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
