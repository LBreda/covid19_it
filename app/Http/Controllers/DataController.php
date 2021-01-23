<?php

namespace App\Http\Controllers;

use App\Models\Datum;
use App\Models\ImmuniDownload;
use App\Models\Notice;
use App\Models\Region;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DataController extends Controller
{
    private function getDataObject(Region $region = null)
    {
        $data = Datum::query()->leftJoin('vaccinations', function (JoinClause $join) {
            $join->on(\DB::raw('DATE(data.date)'), '=', \DB::raw('DATE(vaccinations.date)'));
            $join->on('data.region_id', '=', 'vaccinations.region_id');
        });

        if ($region) {
            $data->where('data.region_id', '=', $region->id);
        }

        $data = $data->get([
            'data.*',
            'vaccinations.daily_first_doses',
            'vaccinations.daily_second_doses',
            DB::raw('(ifnull(vaccinations.daily_first_doses, 0) + ifnull(vaccinations.daily_second_doses, 0)) as daily_vaccinated'),
            'vaccinations.daily_shipped'
        ])->groupBy('date')->mapWithKeys(function (Collection $group) {
            $dataset = collect([
                'hospitalized_home'       => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_home, 0),
                'hospitalized_light'      => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_light, 0),
                'hospitalized_severe'     => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->hospitalized_severe, 0),
                'healed'                  => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->healed, 0),
                'dead'                    => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->dead, 0),
                'tests'                   => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tests, 0),
                'tested'                  => $group->reduce(fn($carry, Datum $datum) => $carry + $datum->tested, 0),
                'daily_vaccinated'        => $group->reduce(fn($carry, $datum) => $carry + $datum->daily_vaccinated, 0),
                'daily_vaccine_shipments' => $group->reduce(fn($carry, $datum) => $carry + $datum->daily_shipped, 0),
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
            $datum = $region->data->sortBy('date')->last();
            $ill = $datum->hospitalized_home + $datum->hospitalized_light + $datum->hospitalized_severe;
            $infected = $ill + $datum->dead + $datum->healed;
            $tested = $datum->tested;
            $daily_vaccinated = $region->vaccinations->reduce(fn($c, Vaccination $d) => $c + $d->daily_vaccinated, 0);
            $daily_vaccine_shipments = $region->vaccinations->reduce(fn($c, Vaccination $d) => $c + $d->daily_shipped, 0);

            return [
                $region->id => [
                    'ill'                     => round(($ill / $region->population) * 1000, 2),
                    'dead'                    => round(($datum->dead / $region->population) * 1000, 2),
                    'infected'                => round(($infected / $region->population) * 1000, 2),
                    'tested'                  => round(($tested / $region->population) * 1000, 2),
                    'severity'                => $region->severity,
                    'daily_vaccinated'        => round(($daily_vaccinated / $region->population) * 1000, 2),
                    'daily_vaccine_shipments' => round(($daily_vaccine_shipments / $region->population) * 1000, 2),
                ],
            ];
        });

        return response()->json($incidence);
    }
}
