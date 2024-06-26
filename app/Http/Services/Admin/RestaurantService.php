<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Restaurant;
use App\Repositories\Interfaces\RestaurantInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantService extends BaseService {

    protected $restaurantRepo;

    public function __construct(RestaurantInterface $restaurantRepo)
    {
        $this -> restaurantRepo = $restaurantRepo;
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Restaurant();
    }

    public function store(Request $request){
        $data = $request->only($this->model->getFillable());
        $file = $this -> uploadImage($request -> file('file'));
        $data['thumb_nail'] = $file['url'];;
        $data['cloud_id'] = $file['cloud_id'];

        DB::beginTransaction();
        try {
            $this -> restaurantRepo -> store($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }

    public function getAllRestaurantKeyValue(){

        $categories = $this->restaurantRepo->allCategory();
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
            'data' => $formattedCategories
        ];
    }

    public function update(Request $request , $id){
        $data = $request->only($this->model->getFillable());
        $restaurant = $this -> query -> find($id);
        $cloudIdOld = $request -> cloud_id_old;
        DB::beginTransaction();
        try {
            if($cloudIdOld){
                $this -> deleteImage($cloudIdOld);
            }
            $file = $this -> uploadImage($request -> file('file'));
            $data['thumb_nail'] = $file['url'];
            $data['cloud_id'] = $file['cloud_id'];
                
            $restaurant -> fill($data);
            $restaurant -> save();
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }
}