<?php

namespace Database\Seeders;

use App\Models\Location_Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location_Type::firstOrCreate(
            [
                'id'=>1,
                'name'=>'airport'    
            ],
        );    
        Location_Type::firstOrCreate(
            [
                'id'=>2,
                'name'=>'hotel'    
            ],
        );    
        Location_Type::firstOrCreate(
            [
                'id'=>3,
                'name'=>'train_station'    
            ],
        );    
     }
}
