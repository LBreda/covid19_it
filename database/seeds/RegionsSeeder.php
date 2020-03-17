<?php

class RegionsSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $handle = fopen(base_path("database/seeds/csv/regions.csv"), "r");
        $geo = json_decode(file_get_contents(base_path("database/seeds/csv/regions.geojson"), "r"));

        foreach ($geo->features as $feature) {
            $region = new \App\Models\Region([
                'id'         => $feature->properties->DatabaseID,
                'name'       => $feature->properties->Regione,
                'code'       => $feature->properties->Code,
                'latitude'   => $feature->properties->Latitude,
                'longitude'  => $feature->properties->Longitude,
                'population' => $feature->properties->Population,
                'phone'      => $feature->properties->PhoneNumbers,
                'geometry'   => json_encode($feature->geometry),
            ]);
            $region->save();
        }
    }
}
