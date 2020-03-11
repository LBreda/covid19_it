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
    public function dashboard(Region $region = null)
    {
        $data = Datum::query();

        $notices = Notice::all();
        if($region) {
            $data->where('region_id', '=', $region->id);
            $notices = $region->notices;
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

        $labels = $data->keys()->map(fn($date) => Carbon::parse($date)->format('d/m/Y'));
        $ill = $data->values()->map(fn(Collection $data) => ($data->get('hospitalized_home') + $data->get('hospitalized_light') + $data->get('hospitalized_severe')));
        $new_cases = $ill->map(function ($item, $key) use ($ill) {
            return $key === 0 ? $item : $item - $ill[$key - 1];
        });
        $hospitalized_home = $data->values()->map(fn(Collection $data) => $data->get('hospitalized_home'));
        $hospitalized_light = $data->values()->map(fn(Collection $data) => $data->get('hospitalized_light'));
        $hospitalized_severe = $data->values()->map(fn(Collection $data) => $data->get('hospitalized_severe'));
        $healed = $data->values()->map(fn(Collection $data) => $data->get('healed'));
        $dead = $data->values()->map(fn(Collection $data) => $data->get('dead'));
        $tested = $data->values()->map(fn(Collection $data) => $data->get('tested'));

        $total_ill = $ill->last();
        $total_healed = $healed->last();
        $total_dead = $dead->last();
        $total_infected = $total_ill + $total_healed + $total_dead;
        $total_tested = $tested->last();
        $letality = 100 * $total_dead / $total_infected;

        return view('dash', compact('region', 'notices', 'labels', 'ill', 'healed', 'dead', 'new_cases', 'hospitalized_home', 'hospitalized_light', 'hospitalized_severe', 'total_ill', 'total_healed', 'total_dead', 'total_infected', 'total_tested', 'letality'));
    }
}
