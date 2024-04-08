<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    
    public function run(){
        $data = [
            [
             'name' => 'Nguyễn Tiến Dũng',
             'email' => 'dungnguyentien140602@gmail.com',
             'password' => bcrypt("dungnguyen123"),
             'is_valid' => 1,
             'address' => 'Hà Nội',
             'birthday' => '2002-06-14',
             'tel' => '0332427837',
             'role' => 1,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s')
            ],
            [
             'name' => 'Nguyễn Mạnh Tiến',
             'email' => 'tiennguyenmanh27092005@gmail.com',
             'password' =>  bcrypt("tiennguyen123"),
             'is_valid' => 1,
             'address' => 'Hà Nội',
             'birthday' => '2005-09-27',
             'tel' => '0866942653',
             'role' => 1,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        User::query()->truncate();
        User::query()->insert($data);
    }
}