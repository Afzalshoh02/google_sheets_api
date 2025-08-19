<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'status', 'price'];

    // Локальный scope для выборки товаров со статусом 'Allowed'
    public function scopeAllowed($query)
    {
        return $query->where('status', 'Allowed');
    }
}