<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate(
            [
                'id'=>1,
                'name'=>'admin'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>2,
                'name'=>'user'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>3,
                'name'=>'sales_agent'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>4,
                'name'=>'travel_agent'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>5,
                'name'=>'driver'    
            ],
        );    
}
}
