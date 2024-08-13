<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\User\ReservationService;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function historyReservation(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $keyWord = $request->get('key_word');
        return [
            'data' => Reservation::query()
                ->where('user_id', auth('api')->user()->id)
                ->whereHas('dish', function ($query) use ($keyWord) {
                    $query->where('name', 'like', '%' . $keyWord . '%');
                })
                ->with(['dish', 'restaurant'])
                ->paginate($perPage)
        ];
    }

    public function historyReservationManager(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        return [
            'data' => Reservation::query()
                ->whereHas('restaurant', function ($query) {
                    $query->where('create_by_user_id', auth('api')->user()->id); // Filter by restaurant owner
                })
                ->with(['restaurant', 'dish']) // Optionally include the restaurant data
                ->paginate($perPage)
        ];
    }

    public function historyReservationDetail($id)
    {
        $data = Reservation::query()->where('id', $id)->with(['restaurant', 'dish'])->first();
        return [
            'data' => $data
        ]; // Optionally include the restaurant
    }

    public function updateReservation(Request $request, $id)
    {
        $status = $request->status;
        $data = Reservation::query()->where('id', $id)->with(['restaurant', 'dish'])->first();
        if (!$data) {
            return $this->respondWithError('Reservation not found');
        }
        DB::beginTransaction();
        try {
            $data->status = $status;
            $data->save();
            DB::commit();
            return [];
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
    }
}
