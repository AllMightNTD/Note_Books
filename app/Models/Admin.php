<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'information_id',
    ];

    public function information(){
        return $this->hasOne(Information::class, 'id' , 'information_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
