<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\Notice;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use function foo\func;

class DataController extends Controller
{
    private function getDataObject (Region $region = null) {
        $data = Datum::query();

        if($region) {
            $data->where('region_id', '=', $region->id);
        }

        $data = $data->get()->groupBy('date')->mapWithKeys(function (Collection $group) {
            $dataset = collect([
                'hospitalized_home'   => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_home, 0),
                'hospitalized_light'  => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_light, 0),
                'hospitalized_severe' => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_severe, 0),
                'healed'              => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->healed, 0),
                'dead'                => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->dead, 0),
                'tested'              => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tested, 0),
            ]);
            return [
                $group->first()->date => $dataset,
            ];
        });

        return $data;
    }

    public function dashboard(Region $region = null)
    {
        $data = Datum::query();

        $notices = Notice::all();
        if($region) {
            $data->where('region_id', '=', $region->id);
            $notices = $region->notices;
        }
        return view('dash', compact('region', 'notices'));
    }

    public function data(Region $region = null)
    {
        $data = $this->getDataObject($region);

        return response()->json($data);
    }
}
