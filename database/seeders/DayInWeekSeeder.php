<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DayInWeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('day_in_weeks')->insert([
            [
                'name' => 'Monday',
                'symbol' => 'Mo',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Tuesday',
                'symbol' => 'Tue',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Wednesday',
                'symbol' => 'Wed',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Thursday',
                'symbol' => 'Thur',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Friday',
                'symbol' => 'Fri',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Saturday',
                'symbol' => 'Sat',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
            [
                'name' => 'Sunday',
                'symbol' => 'Sun',
                'created_at' => date('Y-m-d'),
                'updated_at' => date('Y-m-d')
            ],
        ]);
    }
}
