<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\ImmuniDownload;
use App\Models\Notice;
use App\Models\Region;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    private function getDataObject(Region $region = null)
    {
        // Infection data
        $infections = Datum::query();

        if ($region) {
            $infections->where('data.region_id', '=', $region->id);
        }

        $infections = $infections->select([
            DB::raw('DATE(`data`.`date`) as `date`'),
            'hospitalized_home',
            'hospitalized_home',
            'hospitalized_light',
            'hospitalized_severe',
            'healed',
            'dead',
            'tests',
            'tested',
        ])->get()->groupBy('date')->mapWithKeys(function (Collection $group) {
            $dataset = collect([
                'hospitalized_home'   => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_home, 0),
                'hospitalized_light'  => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_light, 0),
                'hospitalized_severe' => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_severe, 0),
                'healed'              => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->healed, 0),
                'dead'                => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->dead, 0),
                'tests'               => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tests, 0),
                'tested'              => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tested, 0),
            ]);
            return [
                $group->first()->date => $dataset,
            ];
        });

        // Vaccinations data
        $vaccinations = Vaccination::query()
            ->leftJoin('vaccine_suppliers', 'vaccinations.vaccine_supplier_id', '=', 'vaccine_suppliers.id');

        if ($region) {
            $vaccinations->where('vaccinations.region_id', '=', $region->id);
        }

        $vaccinations = $vaccinations->select([
            DB::raw('DATE(`vaccinations`.`date`) as `date`'),
            'vaccinations.daily_first_doses',
            'vaccinations.daily_second_doses',
            'vaccinations.daily_first_boosters',
            'vaccinations.daily_second_boosters',
            'vaccinations.daily_shipped',
            'vaccine_suppliers.doses_needed'
        ])->get()->groupBy(fn(Vaccination $e) => $e->date->format('Y-m-d'))->mapWithKeys(function (Collection $group) {
            $dataset = collect([
                'daily_first_doses'       => $group->reduce(fn($carry, $datum) => $carry + (($datum->doses_needed == 2) ? $datum->daily_first_doses : 0), 0),
                'daily_final_doses'       => $group->reduce(fn($carry, $datum) => $carry + (($datum->doses_needed == 2) ? $datum->daily_second_doses : $datum->daily_first_doses), 0),
                'daily_first_boosters'    => $group->reduce(fn($carry, $datum) => $carry + $datum->daily_first_boosters, 0),
                'daily_second_boosters'   => $group->reduce(fn($carry, $datum) => $carry + $datum->daily_second_boosters, 0),
                'daily_vaccine_shipments' => $group->reduce(fn($carry, $datum) => $carry + $datum->daily_shipped, 0),
            ]);
            $dataset->put('daily_doses', $dataset->get('daily_first_doses') + $dataset->get('daily_final_doses') + $dataset->get('daily_first_boosters') + $dataset->get('daily_second_boosters'));
            return [
                $group->first()->date->format('Y-m-d') => $dataset,
            ];
        });

        $merged = $infections
            ->map(fn($item, $key) => isset($vaccinations[$key]) ? $item->merge($vaccinations[$key]) : $item)
            ->map(fn($item) => $item->union([
                'hospitalized_home'       => 0,
                'hospitalized_light'      => 0,
                'hospitalized_severe'     => 0,
                'healed'                  => 0,
                'dead'                    => 0,
                'tests'                   => 0,
                'tested'                  => 0,
                'daily_doses'             => 0,
                'daily_first_doses'       => 0,
                'daily_final_doses'       => 0,
                'daily_first_boosters'    => 0,
                'daily_second_boosters'   => 0,
                'daily_vaccine_shipments' => 0,
            ]));
        return $merged;
    }

    public function dashboard(Region $region = null)
    {
        $data = Datum::query();

        $notices = Notice::all();
        if ($region) {
            $data->where('region_id', '=', $region->id);
            $notices = $region->notices;
        }
        $last_update_infections = Carbon::parse($data->orderBy('date', 'desc')->first()->date)->format(__('dash.datetime_format'));
        $last_update_vaccinations = Carbon::parse(Vaccination::orderBy('updated_at', 'desc')->first()->updated_at)->format(__('dash.datetime_format'));
        return view('dash', compact('region', 'notices', 'last_update_infections', 'last_update_vaccinations'));
    }

    public function data(Region $region = null)
    {
        $data = $this->getDataObject($region);

        return response()->json($data);
    }

    public function immuniDownloadsData(Region $region = null)
    {
        $data = ImmuniDownload::all()->mapWithKeys(function (ImmuniDownload $download) {
            return [
                $download->date => [
                    'ios_downloads'     => $download->ios_downloads,
                    'android_downloads' => $download->android_downloads,
                    'total_downloads'   => $download->total_downloads,
                ]
            ];
        });
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
            /** @var Datum $datum */
            $datum = $region->data()->orderBy('date', 'desc')->limit(1)->first();
            $population = $region->population;
            $vaccinations = $region->vaccinations;
            $severity = $region->severity;
            $region_id = $region->id;
            unset($region);
            $ill = $datum->hospitalized_home + $datum->hospitalized_light + $datum->hospitalized_severe;
            $infected = $ill + $datum->dead + $datum->healed;
            $tested = $datum->tested;
            $dead = $datum->dead;
            unset($datum);
            $daily_doses = $vaccinations->reduce(fn($c, Vaccination $d) => $c + $d->daily_doses, 0);
            $daily_final_doses = $vaccinations->reduce(fn($c, Vaccination $d) => $c + ($d->vaccine_supplier_id ? (($d->vaccine_supplier->doses_needed == 2) ? $d->daily_second_doses : $d->daily_first_doses) : 0), 0);
            $daily_vaccine_shipments = $vaccinations->reduce(fn($c, Vaccination $d) => $c + $d->daily_shipped, 0);
            unset($vaccinations);

            return [
                $region_id => [
                    'ill'                     => round(($ill / $population) * 1000, 2),
                    'dead'                    => round(($dead / $population) * 1000, 2),
                    'infected'                => round(($infected / $population) * 1000, 2),
                    'tested'                  => round(($tested / $population) * 1000, 2),
                    'severity'                => $severity,
                    'daily_doses'             => round(($daily_doses / $population) * 1000, 2),
                    'daily_final_doses'       => round(($daily_final_doses / $population) * 1000, 2),
                    'daily_vaccine_shipments' => round(($daily_vaccine_shipments / $population) * 1000, 2),
                ],
            ];
        });

        return response()->json($incidence);
    }
}
