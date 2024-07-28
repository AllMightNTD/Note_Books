<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Dish;
use App\Models\Admin\DishImage;
use App\Models\RestaurantImage;
use App\Repositories\Interfaces\DishInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DishService extends BaseService
{

    protected $dishRepo;

    public function __construct(DishInterface $dishRepo)
    {
        $this->dishRepo = $dishRepo;
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Dish();
    }

    public function store(Request $request)
    {
        $imageData = [];
        $data = $request->only($this->model->getFillable());
        $data['price_min'] = (float)$data['price_min'];
        $data['price_max'] = (float)$data['price_max'];

        DB::beginTransaction();
        try {
            $dishId = $this->dishRepo->store($data);
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileData = $this->uploadImage($file);
                    $imageData[] = [
                        'image' => $fileData['url'],
                        'cloud_id' => $fileData['cloud_id'],
                        'dish_id' => $dishId
                    ];
                }
            }
            DishImage::query()->insert($imageData);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function update(Request $request, $id)
    {
        $imageData = [];
        $dish = $this->query->find($id);
        $data = $request->only($this->model->getFillable());
        $data['price_min'] = (float)$data['price_min'];
        $data['price_max'] = (float)$data['price_max'];
        DB::beginTransaction();
        try {
            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $data = $this->uploadImage($file);
                    $imageData[] = [
                        'image' => $data['url'],
                        'cloud_id' => $data['cloud_id'],
                        'dish_id' => $dish['id']
                    ];
                }
            }
            DishImage::query()->insert($imageData);
            $dish->fill($data);
            $dish->save();
            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function applyFilter()
    {
        $category_id = $this->request->get('category_id');

        if ($category_id) {
            $this->query->where('category_id', $category_id);
        }
    }

    public function applySorting()
    {
        return $this->query->with(['images' => function ($query) {
            $query->select(['image', 'dish_id']);
        }]);
    }

    public function show($request, $id)
    {
        $item = $this->query->where('id', $id)
            ->with([
                'images', 'category', 'restaurant', 'restaurant.summaryRestaurant' => function ($query) {
                    $query->select(['parking', 'restaurant_id', 'suitability', 'special_dish', 'space']);
                },
                'restaurant.regulations' => function ($query) {
                    $query->select(['booking_time', 'bill', 'deposit', 'endow', 'reception_time', 'service_charge', 'restaurant_id']);
                },
                'restaurant.utilties' => function ($query) {
                    $query->select(['utilties', 'restaurant_id']);
                },
                'restaurant.openingHours'
            ])->first();

        $dishPropose = $this->query->where('category_id', $item->category_id)
            ->where('id', '!=', $id)
            ->get();

        $item['dish_proposed'] = $dishPropose;
        if (!$item) {
            return $this->sendError();
        }
        return response()->json(
            [
                'data' => $item
            ]
        );
    }
}
