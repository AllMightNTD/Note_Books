<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface InformationInterface extends BaseInterface
{
    public function store($data);
}
