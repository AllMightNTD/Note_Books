<?php

namespace Database\Seeders;

use App\Models\Admin\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $name = ['Nhà hàng', 'Lẩu', 'Buffet', 'Hải Sản', 'Quán Nhậu', 'Món Chay', 'Đặt tiệc', 'Hàn Quốc', 'Nhật Bản', 'Món Âu'];
        foreach ($name as $key => $value) {
            $data[] = [
                'name' => $value,
                'category_id' => 3, // The first category has id 1
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Optional: Truncate the table if you want to start fresh
        // User::query()->truncate();

        SubCategory::query()->insert($data);
    }
}
