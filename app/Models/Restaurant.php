<?php

namespace App\Models;

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
        'contact_phone'
    ];

    public $appends = ['thumbnail_origin'];

    public function getThumbNailOriginAttribute()
    {
        if ($this->thumb_nail) {
            return config('services.cloudinary.url') . $this->thumb_nail;
        }
    }
}
