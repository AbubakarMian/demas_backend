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
            ],
            [
                'id'=>1,
                'name'=>'admin'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>2,
            ],
            [
                'id'=>2,
                'name'=>'User'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>3,
            ],
            [
                'id'=>3,
                'name'=>'Sales Agent'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>4,
            ],
            [
                'id'=>4,
                'name'=>'Travel Agent'    
            ],
        );    
        Role::firstOrCreate(
            [
                'id'=>5,
            ],
            [
                'id'=>5,
                'name'=>'Driver'    
            ],
        );    
}
}
