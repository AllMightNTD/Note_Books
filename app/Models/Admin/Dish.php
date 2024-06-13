<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $table = 'dishs';

    protected $fillable = [
        'name',
        'thumb_nail',
        'price_min',
        'price_max',
        'category_id',
        'restaurant_id',
        'cloud_id'
    ];
}
