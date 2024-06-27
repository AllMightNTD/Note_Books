<?php

namespace App\Models;

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
        'thumb_nail',
        'address',
        'contact_phone',
        'cloud_id',
        'email',
        'type_of_restaurant'
    ];

    public $appends = ['thumbnail_origin'];

    public function getThumbNailOriginAttribute()
    {
        if ($this->thumb_nail) {
            return config('services.cloudinary.url') . $this->thumb_nail;
        }
    }

    public function summaryRestaurant(){
        return $this -> hasOne(SummaryRestaurant::class , 'restaurant_id' , 'id');
    }

    public function regulations(){
        return $this -> hasOne(Regulation::class , 'restaurant_id' , 'id');
    }

    public function utilties(){
        return $this -> hasOne(Utilties::class , 'restaurant_id' , 'id');
    }
}
