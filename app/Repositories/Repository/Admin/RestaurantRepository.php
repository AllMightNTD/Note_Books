<?php
namespace App\Repositories\Repository\Admin;
use App\Models\Restaurant;
use App\Repositories\Interfaces\RestaurantInterface;
use Illuminate\Http\Request;

class RestaurantRepository implements RestaurantInterface {

    protected $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }

    public function store($data){
        return $this -> restaurant::query() -> create($data);
    }

    public function show(Request $request, $id){
        return $this -> restaurant::query()-> where('id', $id) -> first();
    }

    public function allCategory(){
        return $this -> restaurant -> get();
    }
}