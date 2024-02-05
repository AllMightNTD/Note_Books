<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface AuthInterface extends BaseInterface
{
    public function register(Request $request);
}
