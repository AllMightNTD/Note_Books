<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $service;

    public function index(Request $request)
    {
        return $this->service->index($request);
    }

    public function show(Request $request, $id)
    {
        return $this->service->show($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->service->destroy($request, $id);
    }
}
