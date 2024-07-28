<?php

namespace App\Http\Services\User;

use App\Http\Services\BaseService;
use App\Models\Restaurant;
use App\Repositories\Interfaces\RestaurantInterface;

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

    public function show($request, $id)
    {
        // Query with model
        $item = $this->query
            ->where('id', $id)
            ->with(['summaryRestaurant' => function ($query) {
                $query->select(['parking', 'restaurant_id', 'suitability', 'special_dish', 'space']);
            }, 'regulations' => function ($query) {
                $query->select(['booking_time', 'bill', 'deposit', 'endow', 'reception_time', 'service_charge', 'restaurant_id']);
            }, 'utilties' => function ($query) {
                $query->select(['utilties', 'restaurant_id']);
            }, 'images' => function ($query) {
                $query->select(['image', 'restaurant_id']);
            }, 'openingHours'])
            ->first();
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
