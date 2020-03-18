@extends('adminlte::page')

@section('title', config('app.name') . ' | ' . ($region ? $region->name : __('dash.national_data')))

@section('content_header')
    <div class="row">
        <div class="col-md-9"><h1>{{ $region ? $region->name : __('dash.national_data') }}</h1></div>
        <div class="col-md-3 text-right">
            @if(session('lang') == 'it')
                <a href="{{ Request::fullUrlWithQuery(['hl' =>  'en']) }}" class="btn btn-link">English version</a>
            @elseif(session('lang') == 'en')
                <a href="{{ Request::fullUrlWithQuery(['hl' =>  'it']) }}" class="btn btn-link">Versione italiana</a>
            @endif
        </div>
    </div>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-procedures"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.ill') }}</span>
                    <span class="info-box-number" id="total-ill"></span>
                    <small><span class="info-box-number" id="diff-ill"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fas fa-smile"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.healed') }}</span>
                    <span class="info-box-number" id="total-healed"></span>
                    <small><span class="info-box-number" id="diff-healed"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-black"><i class="fas fa-plus-square"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.dead') }}</span>
                    <span class="info-box-number" id="total-dead"></span>
                    <small><span class="info-box-number" id="diff-dead"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fas fa-ambulance"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.infected') }}</span>
                    <span class="info-box-number" id="total-infected"></span>
                    <small><span class="info-box-number" id="diff-infected"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.tested') }}</span>
                    <span class="info-box-number" id="total-tested"></span>
                    <small><span class="info-box-number" id="diff-tested"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-6">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fas fa-skull-crossbones"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.lethality') }}</span>
                    <span class="info-box-number" id="lethality"></span>
                    <small><span class="info-box-number">&nbsp;</span></small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fas fa-hospital"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.hospitalized_light') }}</span>
                    <span class="info-box-number" id="total-hospitalized-light"></span>
                    <small><span class="info-box-number" id="diff-hospitalized-light"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-hospital"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.hospitalized_severe') }}</span>
                    <span class="info-box-number" id="total-hospitalized-severe"></span>
                    <small><span class="info-box-number" id="diff-hospitalized-severe"></span></small>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-fuchsia"><i class="fas fa-hospital"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.hospitalized') }}</span>
                    <span class="info-box-number" id="total-hospitalized"></span>
                    <small><span class="info-box-number" id="diff-hospitalized"></span></small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info text-center" role="alert">
                {{ __('dash.lethality_note') }}
            </div>
        </div>
    </div>
    @if($region)
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    {{ __('dash.emergency_phone_note', ['phone' => $region->phone]) }}
                </div>
            </div>
        </div>
    @endif
    @if($notices->count())
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning text-center" role="alert">
                    @foreach($notices as $notice)
                        <p class="m-0"><strong>{{ $notice->date->format('d/m/Y') }}</strong>
                            - {{ __("dash.notices.{$notice->notice}", ['region' => $notice->region->name]) }}
                        </p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dash.ill_healed_dead') }}</h3>
                    <i class="ml-1 fas fa-exclamation-triangle text-warning" data-toggle="tooltip" data-placement="top"
                       title="{{ __('dash.ill_healed_dead_note') }}"></i>
                    <div class="card-tools">
                        <button class="btn btn-xs btn-default scale-button"
                                type="button">{{ __('dash.change_scale') }}</button>
                    </div>
                </div>

                <div class="card-body card-chart">
                    <canvas id="ill_healed_dead" data-label-ill="{{ __('dash.ill') }}"
                            data-label-healed="{{ __('dash.healed') }}"
                            data-label-dead="{{ __('dash.dead') }}"
                            data-label-new-cases="{{ __('dash.new_cases') }}"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dash.ill_by_severity') }}</h3>
                </div>

                <div class="card-body card-chart">
                    <canvas id="ill_by_severity" data-label-home="{{ __('dash.hospitalized_home') }}"
                            data-label-light="{{ __('dash.hospitalized_light') }}"
                            data-label-severe="{{ __('dash.hospitalized_severe') }}"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dash.ill_by_severity_lines') }}</h3>
                    <div class="card-tools">
                        <button class="btn btn-xs btn-default scale-button"
                                type="button">{{ __('dash.change_scale') }}</button>
                    </div>
                </div>

                <div class="card-body card-chart">
                    <canvas id="ill_by_severity_lines" data-label-home="{{ __('dash.hospitalized_home') }}"
                            data-label-light="{{ __('dash.hospitalized_light') }}"
                            data-label-severe="{{ __('dash.hospitalized_severe') }}"></canvas>
                </div>
            </div>
        </div>
    </div>
    @if(!$region)
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dash.ill_pro_capite') }}</h3>
                    </div>
                    <div id="card-body card-map">
                        <div id="map_ill" class="map-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dash.infected_pro_capite') }}</h3>
                    </div>
                    <div id="card-body card-map">
                        <div id="map_infected" class="map-container"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dash.dead_pro_capite') }}</h3>
                    </div>
                    <div id="card-body card-map">
                        <div id="map_dead" class="map-container"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('dash.data') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route($region ? 'data.region_download' : 'data.total_download', [$region, 'format' => 'csv']) }}"
                           class="btn btn-primary btn-xs">CSV</a>
                        <a href="{{ route($region ? 'data.region_download' : 'data.total_download', [$region, 'format' => 'json']) }}"
                           class="btn btn-primary btn-xs">JSON</a>
                        <a href="{{ route($region ? 'api:region' : 'api:total', [$region]) }}"
                           class="btn btn-primary btn-xs">REST</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                        <tr>
                            <th>{{ __('dash.date') }}</th>
                            <th>{{ __('dash.hospitalized_home') }}</th>
                            <th>{{ __('dash.hospitalized_light') }}</th>
                            <th>{{ __('dash.hospitalized_severe') }}</th>
                            <th>{{ __('dash.healed') }}</th>
                            <th>{{ __('dash.dead') }}</th>
                            <th>{{ __('dash.tested') }}</th>
                        </tr>
                        </thead>
                        <tbody id="data-table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="row mt-4">
        <div class="col-md-12 text-center" style="font-size: 80%">
            <p>{!! __('dash.data_source', ['source' => '<a href="https://github.com/pcm-dpc/COVID-19">Protezione Civile Nazionale</a>']) !!}</p>
            <p>{!! __('dash.created_backed', ['created' => '<a href="https://lbreda.com/">Lorenzo Breda</a>', 'backed' => '<a href="https://twobeesolution.com">TwoBeeSolution S.r.l.</a>']) !!}</p>
            <p>{!! __('dash.git_repos', ['url' => 'https://github.com/LBreda/covid19_it']) !!}</p>
        </div>
    </footer>
@stop

@section('js')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script>
    <script src="{{ asset('scripts/graph_and_boxes.js') }}" id="js-graph-and-boxes"
            data-data-url="{{ $region ? route('api:region', [$region]) : route('api:total') }}"></script>

    @if(!$region)
        <script src="{{ asset('scripts/maps.js') }}" id="js-maps" data-geo-url="{{ asset('data/regions.geojson') }}"
                data-incidence-url="{{ route('api:regions.incidence') }}"></script>
    @endif

    <!-- Matomo -->
    <script type="text/javascript">
        var _paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(["disableCookies"]);
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "{{ env('MATOMO_URL') }}/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '{{ env('MATOMO_SITE_ID') }}']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.type = 'text/javascript';
            g.async = true;
            g.defer = true;
            g.src = u + 'matomo.js';
            s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript><p><img src={{ env('MATOMO_URL') }}/matomo.php?idsite={{ env('MATOMO_SITE_ID') }}&amp;rec=1"
                      style="border:0;" alt=""/></p>
    </noscript>
    <!-- End Matomo Code -->
@stop

@section('css')
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Dati italiani sulla COVID-19 - Italian data about COVID-19">
    <meta property="og:image" content="{{ asset('imgs/social.png') }}">
    <meta property="og:url" content="{{ route('data.total') }}">
    <meta property="og:type" content="website">

    <meta property="twitter:title" content="{{ config('app.name') }}">
    <meta property="twitter:description" content="Dati italiani sulla COVID-19 - Italian data about COVID-19">
    <meta property="twitter:image" content="{{ asset('imgs/social.png') }}">
    <meta property="twitter:url" content="{{ route('data.total') }}">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
          integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
            integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
            crossorigin=""></script>

    <style>
        .info-box .info-box-icon {
            width: 40px;
            padding: 5px;
            font-size: 1.1rem;
        }
        .card-chart {
            width: 100%;
            height: 60vh;
        }

        .map-container {
            height: 700px;
        }
    </style>
@stop
