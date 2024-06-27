<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\Regulation;
use App\Models\Admin\SummaryRestaurant;
use App\Models\Admin\Utilties;
use App\Models\Restaurant;
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

    public function store(Request $request)
    {
        // Restaurant
        $data = $request->only($this->model->getFillable());
        $file = $this->uploadImage($request->file('file'));
        $data['thumb_nail'] = $file['url'];;
        $data['cloud_id'] = $file['cloud_id'];

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
            $restaurantId = $this->restaurantRepo->store($data);
            $summaryData['restaurant_id'] =  $restaurantId;
            $regulationData['restaurant_id'] = $restaurantId;
            SummaryRestaurant::query()->create($summaryData);
            Regulation::query()->create($regulationData);
            Utilties::query()->create(
                [
                    'restaurant_id' => $restaurantId ,
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

        $categories = $this->restaurantRepo->allCategory();
        $formattedCategories = [];

        if ($categories->isNotEmpty()) {
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
        $item = $this->query->where('id', $id)->with(['summaryRestaurant', 'regulations', 'utilties'])->first();
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
