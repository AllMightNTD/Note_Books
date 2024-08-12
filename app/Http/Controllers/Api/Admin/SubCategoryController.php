<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Admin\CategoryRequest;
use App\Http\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class SubCategoryController extends BaseController
{
    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->service->update($request, $id);
    }

    public function getAllCategoriesKeyValue()
    {
        return $this->service->getAllCategoriesKeyValue();
    }

    public function getAllSubCategories()
    {
        return $this->service->getAllSubCategories();
    }
}
