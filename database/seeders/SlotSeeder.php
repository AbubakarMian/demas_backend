<?php

namespace Database\Seeders;

use App\Models\Slot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        Slot::firstOrCreate(
            ['name'=>'Default'],
            [
                'name'=>'Default',
                'start_date'=>0,
                'end_date'=>0,
                'is_default'=>1,
            ]
        ); 
    }
}
