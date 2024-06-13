<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    
    public function run(){
        $data = [
            [
                'name' => 'User',
                'email' => 'user1005@gmail.com',
                'password' => bcrypt("dungnguyen123"),
                'role' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Admin',
                'email' => 'adminfastgo5@gmail.com',
                'password' => bcrypt("admin@123"),
                'role' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];
        // User::query()->truncate();
        User::query()->insert($data);
    }
}