<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Dish;
use App\Repositories\Interfaces\DishInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DishService extends BaseService {

    protected $dishRepo;

    public function __construct(DishInterface $dishRepo)
    {
        $this -> dishRepo = $dishRepo;
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Dish();
    }

    public function store(Request $request){
        $data = $request->only($this->model->getFillable());
        $file = $this -> uploadImage($request -> file('file'));
        $thumbNail = $file['url'];
        $cloudId = $file['cloud_id'];

        $data['thumb_nail'] = $thumbNail;
        $data['cloud_id'] = $cloudId;
        $data['price_min'] = (double)$data['price_min'];
        $data['price_max'] = (double)$data['price_max'];
       
        DB::beginTransaction();
        try {
            $this -> dishRepo -> store($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }
}