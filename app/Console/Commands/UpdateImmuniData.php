<?php

namespace App\Console\Commands;

use App\Helpers\JsonHelper;
use App\Models\Datum;
use App\Models\ImmuniDownload;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class UpdateImmuniData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:update-immuni';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the data from the Immuni public dataset';

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
        $this->info('Downloading the Immuni data...');

        $response = Http::get('https://raw.githubusercontent.com/immuni-app/immuni-dashboard-data/master/dati/andamento-download.json');
        $downloads_national = json_decode(JsonHelper::lint($response->body()));

        $this->info('Downloaded.');

        $this->info('Populating the datatables...');

        DB::table('immuni_downloads')->truncate();

        foreach ($downloads_national as $datum) {
            (new ImmuniDownload([
                'date'              => Carbon::parse($datum->data),
                'ios_downloads'     => $datum->ios,
                'android_downloads' => $datum->android,
            ]))->save();
        }

        $this->info('Immuni data import ended.');

        return 1;
    }
}
