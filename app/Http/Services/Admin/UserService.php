<?php

namespace App\Http\Services\Admin;

use App\Http\Services\BaseService;
use App\Models\Information;
use App\Models\User;
use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\InformationInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService extends BaseService {

    protected $userRepo;
    protected $information;

    public function __construct(UserInterface $user , InformationInterface $information)
    {
        parent::__construct();
        $this -> userRepo = $user;
        $this -> information = $information;
    }

    public function setModel()
    {
        $this->model = new User();
    }

    public function store(Request $request){
        $data = $request->only($this->model->getFillable());
        $data['password'] = $request -> password ?? 'user@123';
        $info = [];
        DB::beginTransaction();
        try {
            $idUser = $this -> userRepo -> store($data);
            $info['user_id'] = $idUser;
            $info['sex'] = $request -> sex;
            $info['contact_phone'] = $request -> contact_phone;
            $this -> information -> store($info);
            
            DB::commit();
            return [];
           
        } catch (\Exception $e) {
            Log::info($e -> getMessage());
            DB::rollBack();
            return $this -> errorResponse();
        }
    }
    public function show(Request $request, $id){
        $data  = $this -> userRepo -> show($request , $id);
        return [
            'data' => $data
        ];
    }

    public function update(Request $request){
        $id = $request -> id;
        $user = $this -> userRepo -> show($request , $id);
        $information = Information::where('user_id', $id)->first();
        DB::beginTransaction();
        try {
          
        if ($information) {
                $information->update([
                    'sex' => $request->get('sex'),
                    'birth_day' => $request->get('birth_day'),
                    'contact_phone' => $request->get('contact_phone'),
                ]);
        }

        $user -> name = $request->name;
        $user -> email = $request -> email;
        $user -> save();

        DB::commit();
        
        return [
            'data' => []
        ];
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return $this->sendError('Failed to updated category.', [$e->getMessage()]);
        }
    }
}