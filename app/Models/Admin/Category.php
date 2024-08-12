<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name'
    ];

    public function dish()
    {
        return $this->hasMany(Dish::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }
}
