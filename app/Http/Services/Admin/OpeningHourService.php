<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Admin\OpeningHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OpeningHourService extends BaseService {
    public function setModel()
    {
        $this->model = new OpeningHour();
    }

    public function store(Request $request){
        $data = $request->only($this->model->getFillable());

        DB::beginTransaction();
        try {
            $this -> model::query() -> create($data);
            DB::commit();
            return [];
        } catch (\Exception $e) {
            //throw $th;
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }

    public function update(Request $request , $id){
        $data = $request->only($this->model->getFillable());
        $openingHour = $this->model->findOrFail($id);

        DB::beginTransaction();
        try {
            $openingHour->fill($data);
            $openingHour->save();
            
            DB::commit();
            return [];
        } catch (\Exception $e) {
            //throw $th;
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }
}