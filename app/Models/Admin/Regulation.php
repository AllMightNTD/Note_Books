<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    protected $table = "table_regulations";

    protected $fillable = [
        'deposit',
        'endow',
        'reception_time',
        'booking_time',
        'bill',
        'service_charge',
        'restaurant_id'
    ];

    public function restaurant(){
        return $this -> belongsTo(Restaurant::class , 'restaurant_id' , 'id');
    }
}
