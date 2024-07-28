<?php

namespace App\Models\Admin;

use App\Models\Admin\Dish;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishImage extends Model
{
    use HasFactory;
    protected $table = 'dishs_image';

    protected $fillable = [
        'image',
        'dish_id',
        'cloud_id',
        'restaurant_id'
    ];

    public $appends = ['image_origin'];

    public function getImageOriginAttribute()
    {
        if ($this->image) {
            return config('services.cloudinary.url') . $this->image;
        }
    }

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'id');
    }
}
