<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{

    public function run()
    {
        $data = [
            'name' => 'Manager Restaurant',
            'email' => 'manager12@gmail.com',
            'password' => Hash::make('admin@123'),
            'role' => 3, // Alternate roles between 0 and 1
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Optional: Truncate the table if you want to start fresh
        // User::query()->truncate();

        User::query()->insert($data);
    }
}
