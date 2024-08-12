<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'user_id',
        'restaurant_id',
        'dish_id',
        'count_adult',
        'count_child',
        'date_order',
        'time_order',
        'note'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dish()
    {
        return $this->belongsTo(User::class, 'dish_id', 'id');
    }

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id', 'id');
    }
}
