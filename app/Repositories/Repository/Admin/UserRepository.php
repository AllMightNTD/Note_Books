<?php
namespace App\Repositories\Repository\Admin;

use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\Request;

class UserRepository implements UserInterface{
    
    protected $user;
    public function __construct(User $user)
    {
        $this -> user = $user;
    }
    
    public function store($data){
        $idUser = $this -> user::query()-> insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $idUser;
    }

    public function show(Request $request , $id){
        return $this -> user::query()->where('id' , $id) -> with(['information']) -> first();
    }
}