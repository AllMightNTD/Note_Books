<?php

namespace App\Models\Admin;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';

    protected $fillable = [
        'name'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
