<?php

namespace App\Console\Commands;

use App\Helpers\JsonHelper;
use App\Models\Datum;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UpdateDpcData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:update-dpc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the data from the Protezione Civile Nazionale public dataset';

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
        $this->info('Downloading the DPC data...');

        $response = Http::get('https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-json/dpc-covid19-ita-regioni.json');
        $data = json_decode(JsonHelper::lint($response->body()));

        $this->info('Downloaded.');

        $this->info('Populating the datatable...');

        DB::table('data')->truncate();

        foreach ($data as $datum) {
            $aliases = [
                '/Friuli V. G./'    => 'Friuli Venezia Giulia',
                '/^Bolzano/'        => 'P.A. Bolzano',
                '/^Trento/'         => 'P.A. Trento',
                '/^Emilia-Romagna/' => 'Emilia Romagna',
            ];
            $datum->denominazione_regione = trim(preg_replace(array_keys($aliases), array_values($aliases), $datum->denominazione_regione));

            $region = Region::where('name', '=', $datum->denominazione_regione)->first();
            if (!$region) {
                $this->error("Skipped {$datum->denominazione_regione}: Region not found");
                continue;
            }
            (new Datum([
                'region_id'           => $region->id,
                'date'                => Carbon::parse($datum->data),
                'hospitalized_home'   => $datum->isolamento_domiciliare,
                'hospitalized_light'  => $datum->ricoverati_con_sintomi,
                'hospitalized_severe' => $datum->terapia_intensiva,
                'healed'              => $datum->dimessi_guariti,
                'dead'                => $datum->deceduti,
                'tests'               => $datum->tamponi,
                'tested'              => $datum->casi_testati,
            ]))->save();
        }

        $this->info('DPC data import ended.');

        return 1;
    }
}
