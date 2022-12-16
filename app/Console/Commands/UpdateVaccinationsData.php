<?php

namespace App\Console\Commands;

use App\Models\Vaccination;
use App\Models\VaccineSupplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateVaccinationsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:update-vaccinations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the data from the Italian vaccines public dataset';

    private static array $regions_ids = [
        'PIE' => 1,
        'VDA' => 2,
        'LOM' => 3,
        'PAB' => 4,
        'PAT' => 5,
        'VEN' => 6,
        'FVG' => 7,
        'LIG' => 8,
        'EMR' => 9,
        'TOS' => 10,
        'UMB' => 11,
        'MAR' => 12,
        'LAZ' => 13,
        'ABR' => 14,
        'MOL' => 15,
        'CAM' => 16,
        'PUG' => 17,
        'BAS' => 18,
        'CAL' => 19,
        'SIC' => 20,
        'SAR' => 21,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Downloading the vaccinations data...');

        $vaccine_suppliers_ids = VaccineSupplier::pluck('id', 'name')->toArray();

        $response = Http::get('https://raw.githubusercontent.com/italia/covid19-opendata-vaccini/master/dati/somministrazioni-vaccini-latest.json');
        $last_update = json_decode(Http::get('https://raw.githubusercontent.com/italia/covid19-opendata-vaccini/master/dati/last-update-dataset.json')->body())->ultimo_aggiornamento;
        $vaccines_raw = json_decode($response->body(), true);

        $this->info('Downloaded.');

        $this->info('Preparing the dataset...');

        $vaccines = collect($vaccines_raw['data'])
            ->sortBy('data')
            ->groupBy('data')
            ->map(fn(Collection $date) => $date->groupBy('area')->map(fn(Collection $area) => $area->groupBy('forn')->map(fn(Collection $fornitore) => $fornitore->reduce(fn(array $c, array $d) => [
                'first'          => $c['first'] + $d['d1'],
                'second'         => $c['second'] + $d['d2'] + $d['dpi'],
                'first_booster'  => $c['first_booster'] + $d['db1'] + ($d['dbi'] ?? 0),
                'second_booster' => $c['second_booster'] + $d['db2'],
            ], ['first' => 0, 'second' => 0, 'first_booster' => 0, 'second_booster' => 0]))));

        $this->info('Populating the datatables...');

        DB::table('vaccinations')->truncate();

        foreach ($vaccines as $date => $regions) {
            foreach ($regions as $region => $suppliers) {
                foreach ($suppliers as $supplier => $datum) {
                    (new Vaccination([
                        'date'                  => Carbon::parse($date),
                        'region_id'             => self::$regions_ids[$region],
                        'vaccine_supplier_id'   => ($supplier != 'ND') ? $vaccine_suppliers_ids[$supplier] : null,
                        'daily_first_doses'     => $datum['first'],
                        'daily_second_doses'    => $datum['second'],
                        'daily_first_boosters'  => $datum['first_booster'],
                        'daily_second_boosters' => $datum['second_booster'],
                        'updated_at'            => substr($last_update, 0, 20),
                        'created_at'            => substr($last_update, 0, 20),
                    ]))->save();
                }
            }
        }

        $this->info('Downloading the vaccines shipments data...');

        $response = Http::get('https://raw.githubusercontent.com/italia/covid19-opendata-vaccini/master/dati/consegne-vaccini-latest.json');
        $vaccines_shipments_raw = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()), true);

        $this->info('Downloaded.');
        $this->info('Preparing the dataset...');

        $vaccines_shipments = collect($vaccines_shipments_raw['data'])
            ->sortBy('data_consegna')
            ->groupBy('data_consegna')
            ->map(fn(Collection $date) => $date->groupBy('area')->map(fn(Collection $area) => $area->reduce(fn(int $c, array $d) => $c + $d['numero_dosi'], 0)));

        $this->info('Populating the datatables...');

        foreach ($vaccines_shipments as $date => $regions) {
            foreach ($regions as $region => $datum) {
                Vaccination::updateOrInsert([
                    'date'      => Carbon::parse($date),
                    'region_id' => self::$regions_ids[$region],
                ],
                    [
                        'date'          => Carbon::parse($date),
                        'region_id'     => self::$regions_ids[$region],
                        'daily_shipped' => $datum,
                    ]);
            }
        }

        $this->info('Vaccination data import ended.');

        return 1;
    }
}
