<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Dish;
use Illuminate\Http\Request;

class DishService extends BaseService {

    protected $userRepo;

    public function __construct()
    {
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Dish();
    }

    public function store(Request $request){
        return 1;
    }
}