<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    //meyimpan data
    protected $fillable = ['name', 'price', 'category', 'image', 'is_available'];
}
