<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Dish;
use App\Models\Admin\DishImage;
use App\Models\Restaurant;
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
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function applyFilter()
    {
        $sub_category_id = $this->request->get('sub_category_id');
        $category_id = $this->request->get('category_id');
        if ($sub_category_id) {
            $this->query->where('sub_category_id', $sub_category_id);
        }
        if ($category_id) {
            $this->query->where('category_id', $category_id);
        }
        if (auth('api')->user()->role == 2) {
            $this->query->whereHas('restaurant', function ($query) {
                $query->where('create_by_user_id', auth('api')->user()->id);
            });
        }
    }

    public function applySorting()
    {
        $this->query->with([
            'images',
            'category',
            'restaurant',
            'restaurant.summaryRestaurant' => function ($query) {
                $query->select(['parking', 'restaurant_id', 'suitability', 'special_dish', 'space']);
            },
            'restaurant.regulations' => function ($query) {
                $query->select(['booking_time', 'bill', 'deposit', 'endow', 'reception_time', 'service_charge', 'restaurant_id']);
            },
            'restaurant.utilties' => function ($query) {
                $query->select(['utilties', 'restaurant_id']);
            },
            'restaurant.openingHours'
        ]);
    }

    public function show($request, $id)
    {
        $userId = auth('api')->user()->id;
        $item =
            Restaurant::query()->where('id', $id)
            ->with([
                'images',
                'category',
                // 'dishs',
                'reservation' => function ($query) use ($id, $userId) {
                    $query->where('user_id', $userId);
                },
                'summaryRestaurant' => function ($query) {
                    $query->select(['parking', 'restaurant_id', 'suitability', 'special_dish', 'space']);
                },
                'regulations' => function ($query) {
                    $query->select(['booking_time', 'bill', 'deposit', 'endow', 'reception_time', 'service_charge', 'restaurant_id']);
                },
                'utilties' => function ($query) {
                    $query->select(['utilties', 'restaurant_id']);
                },
                'openingHours'
            ])->first();
        $dishPropose = Restaurant::query()->where('category_id', $item->category_id)
            ->where('id', '!=', $id)
            ->with(['images'])
            ->get();
        $mainImages = $item->images->first();
        $remainingImages = $item->images->slice(1)->values();
        $item['main_image'] = $mainImages;
        $item['dish_proposed'] = $dishPropose;
        $item['remaining_images'] = $remainingImages;
        if (!$item) {
            return $this->sendError();
        }
        return response()->json(
            [
                'data' => $item
            ]
        );
    }

    public function allDishHome($request)
    {
        $recentlyRestaurant = Restaurant::query()->with(['dishs', 'images'])->take(10)->get();
        $dishBirthday =
            Restaurant::query()->with(['dishs', 'images'])->where('has_birthday_services', 1)->get();
        // Đang giảm giá
        $dishDisCount = Restaurant::query()->with(['dishs', 'images'])->where('has_discount', 1)->get();
        return [
            'newly_created' => $recentlyRestaurant,
            'birthday_services' => $dishBirthday,
            'discount_services' => $dishDisCount,
        ];
    }

    public function searchDish(Request $request)
    {
        $name = $request->get('search');
        $location = $request->get('location');
        $perPage = $request->get('per_page', 10);

        // Start the query
        $query = Restaurant::query();

        // Apply the search filter if 'name' is provided
        if ($name && $name !== '') {
            \Log::info('name: ' . $name);
            $query->where('name', 'like', '%' . $name . '%');
        }

        // Paginate the results with related images and restaurant data
        $results = $query->with(['images', 'restaurant'])->paginate($perPage);

        return $results;
    }
}
