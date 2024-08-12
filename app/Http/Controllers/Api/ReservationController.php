<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\User\ReservationService;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends BaseController
{
    //
    public function __construct(ReservationService $reservationService)
    {
        $this->service = $reservationService;
    }
    public function store(Request $request)
    {
        return $this->service->store($request);
    }

    public function historyReservation()
    {
        return Reservation::query()->where('user_id', auth('api')->user()->id)->get();
    }
}
