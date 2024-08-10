<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Ba Đình',
            ],
            [
                'name' => 'Thanh Xuân',
            ],
            [
                'name' => 'Tây Hồ',
            ],
            [
                'name' => 'Cầu Giấy',
            ],
            [
                'name' => 'Hai Bà Trưng',
            ],
            [
                'name' => 'Hoàn Kiếm',
            ],
            [
                'name' => 'Hoàng Mai',
            ],
        ];
        // User::query()->truncate();
        DB::table('area')->insert($data);
    } 
}
