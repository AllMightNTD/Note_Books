<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Information;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformationSeeder extends Seeder {
    
    public function run(){
        Information::query()->insert([
           [
            'email' => 'dungnguyentien140602@gmail.com',
            'birthday' => '14-06-2002',
            'sex' => 1,
            'address' => 'Hà Nội',
            'created_at' => date('Y-m-d H:i:s')
           ],
           [
            'email' => 'tiennguyenmanh2905@gmail.com',
            'birthday' => '27-09-2005',
            'sex' => 2,
            'address' => 'Hà Nội',
            'created_at' => date('Y-m-d H:i:s')
           ]
        ]);
    }
}