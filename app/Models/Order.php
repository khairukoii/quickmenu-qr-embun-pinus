<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Buka gembok keamanan agar nama pelanggan bisa disimpan
    protected $fillable = ['table_number', 'customer_name', 'payment_method', 'items', 'total_price', 'status'];
}