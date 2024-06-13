<?php
namespace App\Repositories\Repository\Admin;

use App\Models\Admin\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryInterface {

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function store($data){
        return $this -> category::query() -> create($data);
    }

    public function show(Request $request, $id){
        return $this -> category::query()-> where('id', $id) -> first();
    }

    public function update($data, $id)
    {
        $category = $this->category->findOrFail($id);
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function allCategory(){
        return $this -> category -> get();
    }
}