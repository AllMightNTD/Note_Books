<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Dish;
use App\Models\Admin\DishImage;
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
        $sub_category_id = $this->request->get('sub_category_id');
        $category_id = $this->request->get('category_id');
        if ($sub_category_id) {
            $this->query->where('sub_category_id', $sub_category_id);
        }
        if ($category_id) {
            $this->query->where('category_id', $category_id);
        }
        $this->query->whereHas('restaurant', function ($query) {
            $query->where('create_by_user_id', auth('api')->user()->id);
        });
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
            $this->query->where('id', $id)
            ->with([
                'images',
                'category',
                'restaurant',
                'reservation' => function ($query) use ($id, $userId) {
                    $query->where('dish_id', $id)
                        ->where('user_id', $userId);
                },
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
            ])->first();
        $dishPropose = $this->query->where('category_id', $item->category_id)
            ->where('id', '!=', $id)
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
        // Mới tạo gần đây
        $recentlyRestaurant =
            Dish::join('restaurants', 'dishs.restaurant_id', '=', 'restaurants.id')
            ->orderBy('restaurants.created_at', 'desc')
            ->take(10)
            ->select('dishs.*') // Chọn các cột từ bảng dishes
            ->with(['restaurant', 'images']) // Tải quan hệ restaurant nếu cần
            ->get();
        // Phù hợp tổ chức sinh nhật
        $dishBirthday = $this->query->whereHas('restaurant', function ($query) {
            $query->where('has_birthday_services', 1);
        })->with(['restaurant', 'images'])->get();

        // Đang giảm giá
        $dishDisCount = $this->query->whereHas('restaurant', function ($query) {
            $query->where('has_discount', 1);
        })->with(['restaurant', 'images'])->get();
        \Log::info('restaurant' . $dishDisCount);

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
        if (!empty($name)) {
            $this->query->where('name', 'like', '%' . $name . '%');
        }
        return $this->query->with(['images', 'restaurant'])->paginate($perPage);
    }
}
