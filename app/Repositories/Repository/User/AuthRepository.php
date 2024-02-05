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

    public function getArrayByCompany($company_id)
    {
        return $this->user::query()->where('company_id', $company_id)->pluck('name')->toArray();
    }

    public function register(Request $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);
        return $this->user::query()->create($request->all());
    }

    public function show(Request $request, $id)
    {
        return $this->user::query()->where('id', $id)->first();
    }
}
