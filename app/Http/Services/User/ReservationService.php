<?php

namespace App\Http\Services\User;

use App\Http\Services\BaseService;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationService extends BaseService
{
    public function setModel()
    {
        $this->model = new Reservation();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->only($this->model->getFillable());
            $data['user_id'] = auth('api')->user()->id;
            Reservation::query()->create($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            //throw $th;
            \Log::info($e->getMessage());
            DB::rollBack();
        }
    }
}
