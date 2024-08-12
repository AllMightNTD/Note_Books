<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'name',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Dish::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->hasMany(Dish::class, 'sub_category_id', 'id');
    }
}
