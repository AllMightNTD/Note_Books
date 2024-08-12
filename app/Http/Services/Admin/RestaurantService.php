<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Regulation;
use App\Models\Admin\SummaryRestaurant;
use App\Models\Admin\Utilties;
use App\Models\Restaurant;
use App\Models\RestaurantImage;
use App\Repositories\Interfaces\RestaurantInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestaurantService extends BaseService
{

    protected $restaurantRepo;

    public function __construct(RestaurantInterface $restaurantRepo)
    {
        $this->restaurantRepo = $restaurantRepo;
        parent::__construct();
    }

    public function setModel()
    {
        $this->model = new Restaurant();
    }

    public function applySorting()
    {
        $user = auth('api')->user();
        return
            $this->query->where('create_by_user_id', $user->id)->with(['images' => function ($query) {
                $query->select(['image', 'restaurant_id']);
            }, 'area']);
    }
    public function store(Request $request)
    {
        $imageData = [];
        // Restaurant
        $data = $request->only($this->model->getFillable());
        $data['create_by_user_id'] = auth('api')->user()->id;

        // Tóm tắt chi tiết
        $summary = new SummaryRestaurant();
        $summaryData = $request->only($summary->getFillable());

        // Quy định
        $regulation = new Regulation();
        $regulationData = $request->only($regulation->getFillable());

        if ($request->utilties) {
            $utilties = json_decode($request->utilties, true);
        }

        DB::beginTransaction();
        try {
            $data['has_discount'] = $data['has_discount'] === '1';
            $data['has_birthday_services'] = $data['has_birthday_services'] === '1';
            $data['discount'] = (int) $data['discount'];
            $data['area_id'] = 1;
            $restaurantId = $this->restaurantRepo->store($data);
            if ($request->hasfile('files')) {
                foreach ($request->file('files') as $file) {
                    $data = $this->uploadImage($file);
                    $imageData[] = [
                        'image' => $data['url'],
                        'cloud_id' => $data['cloud_id'],
                        'restaurant_id' => $restaurantId
                    ];
                }
            }
            $summaryData['restaurant_id'] =  $restaurantId;
            $regulationData['restaurant_id'] = $restaurantId;
            RestaurantImage::query()->insert($imageData);
            SummaryRestaurant::query()->create($summaryData);
            Regulation::query()->create($regulationData);
            Utilties::query()->create(
                [
                    'restaurant_id' => $restaurantId,
                    'utilties' => json_encode($utilties)
                ]
            );

            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function getAllRestaurantKeyValue()
    {

        $options = $this->restaurantRepo->allOption();
        $formattedOptions = [];

        if ($options->isNotEmpty()) {
            foreach ($options as $option) {
                $formattedOptions[] = [
                    "label" => $option->name,
                    "value" => $option->id
                ];
            }
        }

        return [
            'data' => $formattedOptions
        ];
    }

    public function update(Request $request, $id)
    {
        // Tóm tắt chi tiết
        $summary = new SummaryRestaurant();
        $summaryModel = $request->only($summary->getFillable());
        // Restaurant
        $data = $request->only($this->model->getFillable());
        $restaurant = $this->query->find($id);
        $cloudIdOld = $request->cloud_id_old;
        // Quy định
        $regulation = new Regulation();
        $regulationModel = $request->only($regulation->getFillable());
        // Tiện ích
        if ($request->utilties) {
            $utilties = json_decode($request->utilties, true);
        }

        DB::beginTransaction();
        try {
            if ($cloudIdOld && $request->file('file')) {
                $this->deleteImage($cloudIdOld);;
            }
            if ($request->file('file')) {
                $file = $this->uploadImage($request->file('file'));
                $data['thumb_nail'] = $file['url'];
                $data['cloud_id'] = $file['cloud_id'];
            }

            $restaurant->fill($data);
            $restaurant->save();

            SummaryRestaurant::updateOrCreate(
                [
                    'restaurant_id' => $id
                ],
                $summaryModel
            );

            Regulation::updateOrCreate(
                [
                    'restaurant_id' => $id
                ],
                $regulationModel
            );
            if ($request->utilties) {
                Utilties::updateOrCreate(
                    [
                        'restaurant_id' => $id
                    ],
                    ['utilties' => json_encode($utilties)]
                );
            }


            DB::commit();
            return [];
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->errorResponse();
        }
    }

    public function show($request, $id)
    {
        // Query with model
        $item = $this->query
            ->where('id', $id)
            ->with([
                'summaryRestaurant' => function ($query) {
                    $query->select(['parking', 'restaurant_id', 'suitability', 'special_dish', 'space']);
                },
                'regulations' => function ($query) {
                    $query->select(['booking_time', 'bill', 'deposit', 'endow', 'reception_time', 'service_charge', 'restaurant_id']);
                },
                'utilties' => function ($query) {
                    $query->select(['utilties', 'restaurant_id']);
                },
                'images' => function ($query) {
                    $query->select(['image', 'restaurant_id']);
                },
                'area'
            ])
            ->first();

        // // Query with raw sql
        // $item = DB::table('restaurants')
        //         ->join('table_summary_restaurant','restaurants.id','=','table_summary_restaurant.restaurant_id')
        //         ->join('table_regulations','restaurants.id','=','table_regulations.restaurant_id')
        //         ->join('table_utilties','restaurants.id','=','table_utilties.restaurant_id')
        //         ->select('restaurants.*','table_summary_restaurant.*','table_regulations.*','table_utilties.*')
        //         ->where('restaurants.id', $id)
        //         ->first();

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
