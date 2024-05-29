<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Category;

class CategoryService extends BaseService {

    protected $userRepo;

    public function __construct()
    {
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Category();
    }
}