<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Category;
use App\Models\Admin\Dish;
use App\Models\Admin\SubCategory;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubCategoryService extends BaseService
{

    public function __construct()
    {
        parent::__construct();
    }
    public function setModel()
    {
        $this->model = new SubCategory();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only($this->model->getFillable());
            $this->model::query()->create($data);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
