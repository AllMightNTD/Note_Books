<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface UserInterface extends BaseInterface
{
    public function store($data);
}
