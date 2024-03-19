<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    
    public function run(){
        User::query()->insert([
           [
            'name' => 'Nguyễn Tiến Dũng',
            'email' => 'dungnguyentien140602@gmail.com',
            'password' => bcrypt("dungnguyen123")
           ],
           [
            'name' => 'Nguyễn Mạnh Tiến',
            'email' => 'tiennguyenmanh27092005@gmail.com',
            'password' =>  bcrypt("tiennguyen123")
           ]
        ]);
    }
}