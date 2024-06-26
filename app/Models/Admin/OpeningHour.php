<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
use Google\Type\DayOfWeek;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OpeningHour extends Model
{
    use HasFactory;

    protected $table = 'opening_hours';

    protected $fillable = [
        'day_in_week_id',
        'open_time',
        'close_time',
        'restaurant_id'
    ];

    public $appends = ['day_of_week_name' , 'restaurant_name'];

    public function restaurant(){
        return $this -> hasOne(Restaurant::class , 'restaurant_id' , 'id');
    }

    public function getDayOfWeekNameAttribute(){
        if ($this->day_in_week_id) {
            return DB::table('day_in_weeks')->where('id' , $this -> day_in_week_id) -> first() -> name;
        }
    }

    public function getRestaurantNameAttribute(){
        if ($this->restaurant_id) {
            return DB::table('restaurants')->where('id' , $this -> restaurant_id) -> first() -> name;
        }
    }
}
