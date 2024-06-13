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
        $file = $this -> uploadImage($request -> file('file'));
        $thumb_nail = $file['url'];
        $cloudId = $file['cloud_id'];
        $name = $request -> name;
        $address = $request -> address;
        $contact_phone = $request -> contact_phone;
        $data = [
            'name' => $name,
            'address' => $address,
            'thumb_nail' => $thumb_nail,
            'contact_phone' => $contact_phone,
            'cloud_id' => $cloudId
        ];

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

    public function getAllCategoriesKeyValue(){

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

        return $formattedCategories;
    }
}