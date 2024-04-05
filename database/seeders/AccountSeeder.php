<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    
    public function run(){
        $data = [
            [
             'name' => 'Nguyễn Tiến Dũng',
             'email' => 'dungnguyentien140602@gmail.com',
             'password' => bcrypt("dungnguyen123"),
             'admin_id' => 1,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s')
            ],
            [
             'name' => 'Nguyễn Mạnh Tiến',
             'email' => 'tiennguyenmanh27092005@gmail.com',
             'password' =>  bcrypt("tiennguyen123"),
             'admin_id' => 2,
             'created_at' => date('Y-m-d H:i:s'),
             'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        User::query()->truncate();
        User::query()->insert($data);
    }
}