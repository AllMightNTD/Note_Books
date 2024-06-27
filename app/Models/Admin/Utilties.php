<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilties extends Model
{
    use HasFactory;

    protected $table = "table_utilties";

    protected $fillable = [
        'utilties',
        'restaurant_id'
    ];

    public function restaurant(){
        return $this -> belongsTo(Restaurant::class , 'restaurant_id' , 'id');
    }
}
