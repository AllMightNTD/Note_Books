<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantImage extends Model
{
    use HasFactory;
    protected $table = 'restaurant_image';

    protected $fillable = [
        'image',
        'cloud_id',
        'address',
        'restaurant_id'
    ];

    public $appends = ['image_origin'];

    public function getImageOriginAttribute()
    {
        if ($this->image) {
            return config('services.cloudinary.url') . $this->image;
        }
    }

    public function restaurant(){
        return $this -> belongsTo(Restaurant::class , 'restaurant_id' , 'id');
    }
}
