<?php

class RegionsSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $geo = json_decode(file_get_contents(base_path("database/seeds/csv/regions.geojson"), "r"));

        foreach ($geo->features as $feature) {
            $region = \App\Models\Region::find($feature->properties->DatabaseID);
            $regionData = [
                'id'         => $feature->properties->DatabaseID,
                'name'       => $feature->properties->Regione,
                'code'       => $feature->properties->Code,
                'latitude'   => $feature->properties->Latitude,
                'longitude'  => $feature->properties->Longitude,
                'population' => $feature->properties->Population,
                'phone'      => $feature->properties->PhoneNumbers,
                'geometry'   => json_encode($feature->geometry),
            ];
            if(!$region) {
                $region = new \App\Models\Region();
                $region->save();
            } else {
                $region->update($regionData);
            }
        }
    }
}
