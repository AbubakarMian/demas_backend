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
            ['name'=>'Airport'],
            [
                'name'=>'Airport'
            ],
        );    
        Location_Type::firstOrCreate(
            ['name'=>'Hotel'],
            [
                'name'=>'Hotel'    
            ],
        );    
        // Location_Type::firstOrCreate(
        //     [
        //         'id'=>3,
        //         'name'=>'BusStation'    
        //     ],
        // );    
     }
}
