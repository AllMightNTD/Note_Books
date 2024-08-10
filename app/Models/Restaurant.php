<?php

namespace App\Models;

use App\Models\Admin\Area;
use App\Models\Admin\Dish;
use App\Models\Admin\OpeningHour;
use App\Models\Admin\Regulation;
use App\Models\Admin\SummaryRestaurant;
use App\Models\Admin\Utilties;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    protected $fillable = [
        'name',
        'address',
        'contact_phone',
        'email',
        'type_of_restaurant',
        'area_id',
        'create_by_user_id'
    ];

    public function summaryRestaurant()
    {
        return $this->hasOne(SummaryRestaurant::class, 'restaurant_id', 'id');
    }

    public function regulations()
    {
        return $this->hasOne(Regulation::class, 'restaurant_id', 'id');
    }

    public function utilties()
    {
        return $this->hasOne(Utilties::class, 'restaurant_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(RestaurantImage::class, 'restaurant_id', 'id');
    }

    public function dishs()
    {
        return $this->hasMany(Dish::class, 'restaurant_id', 'id');
    }

    public function openingHours()
    {
        return $this->hasOne(OpeningHour::class, 'restaurant_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'create_by_user_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
