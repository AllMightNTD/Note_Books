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
        $thumb_nail = $this -> uploadImage($request -> file('file'));
        $name = $request -> name;
        $address = $request -> address;
        $contact_phone = $request -> contact_phone;
        $data = [
            'name' => $name,
            'address' => $address,
            'thumb_nail' => $thumb_nail,
            'contact_phone' => $contact_phone
        ];

        DB::beginTransaction();
        try {
            $this -> restaurantRepo -> store($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Hệ thống đang bảo trì'], 500);
        }
    }
}