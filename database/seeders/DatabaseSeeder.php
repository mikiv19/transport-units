<?php

namespace Database\Seeders;
use app\Models\TransportUnit;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder{
    public function run(){
    TransportUnit::factory()->count(50)->create();  // Creates 50 transport units
}
}
