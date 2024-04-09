<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder {
    
    public function run(){
        
        $data = [];
        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'name' => 'Người dùng ' . ($i + 1),
                'email' => 'user' . ($i + 1) . '@example.com',
                'password' => bcrypt("password123"),
                'is_valid' => 1,
                'address' => 'Địa chỉ ' . ($i + 1),
                'birthday' => '2000-01-01',
                'tel' => '0123456789',
                'role' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        User::query()->truncate();
        User::query()->insert($data);
    }
}