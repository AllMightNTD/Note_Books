<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface BaseInterface
{
    public function show(Request $request, $id);
}
