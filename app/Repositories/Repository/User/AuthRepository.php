<?php

namespace App\Repositories\Repository\User;

use App\Models\Information;
use App\Models\User;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthInterface
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getArrayByCompany($company_id)
    {
        return $this->user::query()->where('company_id', $company_id)->pluck('name')->toArray();
    }

    public function register(Request $request)
    {
        \Log::info('$request->manage_restaurant' . $request->manage_restaurant);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->manage_restaurant == 1 ? 2 : 0,
        ];
        return $this->user::query()->create($data);
    }

    public function show(Request $request, $id)
    {
        return $this->user::query()->where('id', $id)->with('information')->first();
    }

    public function information($id)
    {
        return DB::table('information')->where('user_id', $id)->first();
    }
}
