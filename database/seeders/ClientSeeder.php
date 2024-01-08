<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;


class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::firstOrCreate(
            ['client_id' => 'demas-app-mobile'],
            ['client_secret' => 'ZGVtYXMtYXBwLW1vYmlsZTtaR1Z0WVhNdFlYQndMVzF2WW1sc1pRPT0=']
        );
       
    }
}
