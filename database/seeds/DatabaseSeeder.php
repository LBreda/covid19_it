<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RegionsSeeder::class);
        $this->call(RestrictionTypesSeeder::class);
        $this->call(VaccineSuppliersSeeder::class);
        $this->call(DataSeeder::class);
    }
}
