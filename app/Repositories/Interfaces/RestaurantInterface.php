<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface RestaurantInterface extends BaseInterface
{
    public function store($data);

    public function allOption();
}
