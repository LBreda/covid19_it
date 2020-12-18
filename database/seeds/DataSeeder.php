<?php

namespace Database\Seeders;

class DataSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        \Illuminate\Support\Facades\Artisan::call('covid:update');
    }
}
