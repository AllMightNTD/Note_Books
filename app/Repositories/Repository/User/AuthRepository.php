<?php

namespace App\Repositories\Repository\User;

use App\Models\User;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request){
       $perPage = $request -> per_page;
       return User::query()->orderBy('created_at', 'desc')->paginate($perPage);   
    }

    public function register(Request $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);
        return $this->user::query()->create($request->all());
    }

    public function show(Request $request, $id)
    {
         $data = User::query()->where('id' , $id)->where('is_valid',1)-> first();
         return $data;
    }

}
