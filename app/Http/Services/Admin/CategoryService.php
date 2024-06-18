<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Category;
use App\Models\Admin\Dish;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryService extends BaseService {

    protected $categoryRepo;

    public function __construct(CategoryInterface $categoryRepo)
    {
        $this -> categoryRepo = $categoryRepo;
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Category();
    }

    public function store(Request $request){
        $data = $request->only($this->model->getFillable());

        DB::beginTransaction();
        try {
            $this -> categoryRepo -> store($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only($this->model->getFillable());

        try {
            DB::beginTransaction();
            $this->categoryRepo->update($data, $id);
            DB::commit();

            return $this->sendResponse($id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendError('Failed to updated category.', [$e->getMessage()]);
        }
    }

    public function getAllCategoriesKeyValue(){

        $categories = $this->categoryRepo->allCategory();
        $formattedCategories = [];

        if($categories->isNotEmpty()){
            foreach ($categories as $category) {
                $formattedCategories[] = [
                    "label" => $category->name,
                    "value" => $category->id
                ];
            }
        }

        return [
            'data' =>  $formattedCategories
        ];
    }

    public function destroy(Request $request, $id, $isForceDelete = false){
        $dish = Dish::whereHas('category', function ($q) use ($id) {
            $q->where('id', $id);
        })->exists();
        if($dish){
            return $this -> sendError('Đã tồn tại món ăn ứng với danh mục này , không thể xóa !!!' , [] , 403);
        }else{
            $model = $this->query->findOrFail($id);
            $model->delete();
            return response()->json(['message' => 'Deleted successfully']);
        }
    }
}