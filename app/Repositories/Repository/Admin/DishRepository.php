<?php

namespace App\Repositories\Repository\Admin;

use App\Models\Admin\Dish;
use App\Repositories\Interfaces\DishInterface;
use Illuminate\Http\Request;

class DishRepository implements DishInterface
{

    protected $dish;

    public function __construct(Dish $dish)
    {
        $this->dish = $dish;
    }

    public function store($data)
    {
        return $this->dish::query()->insertGetId($data);
    }

    public function show(Request $request, $id)
    {
        return $this->dish::query()->where('id', $id)->first();
    }

    public function update($data, $id)
    {
        $category = $this->dish->findOrFail($id);
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function allCategory()
    {
        return $this->dish->get();
    }
}
