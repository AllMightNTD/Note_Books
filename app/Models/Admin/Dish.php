<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
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

    public $appends = ['thumbnail_origin' , 'restaurant_name' , 'category_name'];

    public function getThumbNailOriginAttribute()
    {
        if ($this->thumb_nail) {
            return config('services.cloudinary.url') . $this->thumb_nail;
        }
    }

    public function getRestauRantNameAttribute()
    {
        if ($this->restaurant_id) {
            return Restaurant::query()->where('id' , $this -> restaurant_id) -> first() -> name;
        }
    }

    public function getCategoryNameAttribute()
    {
        if ($this->category_id) {
            return Category::query()->where('id' , $this -> category_id) -> first() -> name;
        }
    }

    public function category(){
        return $this -> belongsTo(Category::class , 'category_id' , 'id');
    }
}
