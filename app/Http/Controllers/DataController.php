<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use Illuminate\Support\Collection;

class DataController extends Controller
{
    public function getStateJSON()
    {
        $data = Datum::all()->groupBy('date')->mapWithKeys(function (Collection $group) {
            $dataset = collect([
                'hospitalized_home'   => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_home, 0),
                'hospitalized_light'  => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_light, 0),
                'hospitalized_severe' => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_severe, 0),
                'healed' => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->healed, 0),
                'dead'   => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->dead, 0),
                'tested' => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tested, 0),
            ]);
            return [
                $group->first()->date => $dataset,
            ];
        });
        return response()->json($data);
    }
}
