<?php

namespace App\Console\Commands;

use App\Models\Datum;
use App\Models\ImmuniDownload;
use App\Models\Region;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpParser\ErrorHandler\Collecting;

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
     * @return void
     */
    public function handle()
    {
        $this->info('Downloading the vaccines data...');

        $response = Http::get('https://raw.githubusercontent.com/italia/covid19-opendata-vaccini/master/dati/somministrazioni-vaccini-latest.json');
        $vaccines_raw = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()), true);

        $this->info('Downloaded.');

        $this->info('Preparing the dataset...');

        $vaccines = collect($vaccines_raw['data'])
            ->sortBy('data_somministrazione')
            ->groupBy('data_somministrazione')->map(function (Collection $date) {
                return $date->groupBy('area')->map(function (Collection $area) {
                    return $area->reduce(function (int $c, array $d) {
                        return $c + $d['sesso_maschile'] + $d['sesso_femminile'];
                    }, 0);
                });
            });

        $this->info('Populating the datatables...');

        DB::table('vaccinations')->truncate();

        foreach ($vaccines as $date => $regions) {
            foreach ($regions as $region => $datum) {
                (new Vaccination([
                    'date'             => Carbon::parse($date),
                    'region_id'        => self::$regions_ids[$region],
                    'daily_vaccinated' => $datum,
                ]))->save();
            }
        }
    }
}
