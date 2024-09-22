<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create(['name' => 'Coimbatore', 'address' => '123 Main St']);
        Location::create(['name' => 'Chennai', 'address' => '456 Side St']);
        Location::create(['name' => 'Ooty', 'address' => '789 Side St']);
        Location::create(['name' => 'Salem', 'address' => '456 Main St']);
    }
}
