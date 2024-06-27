<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummaryRestaurant extends Model
{
    use HasFactory;

    protected $table = 'table_summary_restaurant';

    protected $fillable = [
        'suitability',
        'special_dish',
        'space',
        'parking',
        'speciality',
        'restaurant_id',
    ];

    public function restaurant(){
        return $this -> belongsTo(Restaurant::class , 'restaurant_id' , 'id');
    }

}
