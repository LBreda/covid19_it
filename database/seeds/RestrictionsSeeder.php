<?php

namespace Database\Seeders;

class RestrictionsSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $handle = fopen(base_path("database/seeds/csv/restrictions.csv"), "r");

        if (($handle !== false)) {
            $heading = fgetcsv($handle, 1000);
            while (($row = fgetcsv($handle, 1000)) !== false) {
                $rowAssoc = array_combine($heading, $row);

                $datum = new \App\Models\Restriction($rowAssoc);
                $datum->save();

            }
            fclose($handle);
        }
    }
}
