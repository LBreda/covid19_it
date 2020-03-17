<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\Notice;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DataController extends Controller
{
    private function getDataObject(Region $region = null)
    {
        $data = Datum::query();

        if ($region) {
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
        if ($region) {
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

    public function downloadData(Request $request, Region $region = null)
    {
        $format = $request->input('format');
        $data = $this->getDataObject($region);

        $filename = $region ? $region->name : __('dash.national_data');

        if ($format === 'csv') {
            $callback = function () use ($data) {
                $fp = fopen('php://output', 'w');

                fputcsv($fp, ['date', ...$data->first()->keys()->toArray()]);
                $data->each(function (Collection $datum, $date) use ($fp) {
                    fputcsv($fp, [Carbon::parse($date)->format('Y-m-d'), ...$datum->values()->toArray()]);
                });
                fclose($fp);
            };
            return response()->stream($callback, 200, ["Content-type" => "text/csv", "Content-Disposition" => "attachment; filename=\"{$filename}.csv\"",]);
        } else {
            return response($this->getDataObject($region)->toJson(), 200, ["Content-type" => "application/json", "Content-Disposition" => "attachment; filename=\"{$filename}.json\"",]);
        }
    }

    public function regionalIncidence()
    {
        $incidence = Region::all()->mapWithKeys(function (Region $region) {
            $datum = $region->data->sortBy('date')->last();
            $ill = $datum->hospitalized_home + $datum->hospitalized_light + $datum->hospitalized_severe;
            $infected = $ill + $datum->dead + $datum->healed;

            return [
                $region->id => [
                    'ill'      => round(($ill / $region->population) * 10000, 2),
                    'dead'     => round(($datum->dead / $region->population) * 10000, 2),
                    'infected' => round(($infected / $region->population) * 10000, 2),
                ],
            ];
        });

        return response()->json($incidence);
    }
}
