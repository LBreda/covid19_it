<?php
namespace Database\Seeders;

class RegionsSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $handle = fopen(base_path("database/seeds/csv/regions.csv"), "r");

        if (($handle !== false)) {
            $heading = fgetcsv($handle, 1000);
            while (($row = fgetcsv($handle, 1000)) !== false) {
                $rowAssoc = array_combine($heading, $row);

                $datum = new \App\Models\Region($rowAssoc);
                $datum->save();

            }
            fclose($handle);
        }
    }
}
