<?php

namespace App\Providers;

use App\Models\Region;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            $event->menu->add([
                'text'        => __('dash.national_data'),
                'url'         => route('data.total'),
                'icon'        => 'fas fa-chart-bar',
            ]);
            $event->menu->add(['header' => __('dash.regions')]);
            Region::all()->sortBy('name')->each(function (Region $region) use ($event) {
                $event->menu->add([
                    'text' => $region->name,
                    'url'  => route('data.region', [$region]),
                    'icon' => 'fas fa-chart-bar',
                ]);
            });
        });
    }
}
