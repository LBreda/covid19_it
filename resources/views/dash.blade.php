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
                    <div class="card-tools">
                        <button class="btn btn-xs btn-default scale-button"
                                type="button">{{ __('dash.change_scale') }}</button>
                    </div>
                </div>

                <div class="card-body card-chart">
                    <canvas id="ill_healed_dead"></canvas>
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
                    <canvas id="ill_by_severity"></canvas>
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
                    <canvas id="ill_by_severity_lines"></canvas>
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
        // Camvases
        let ill_healed_dead = document.getElementById('ill_healed_dead');
        let ill_by_severity = document.getElementById('ill_by_severity');
        let ill_by_severity_lines = document.getElementById('ill_by_severity_lines');

        // Functions
        /**
         * Removes legend if the chart is too small
         * @param chart
         * @param size
         */
        let chartOnResize = (chart, size) => {
            let showLegend = size.width > 800;
            chart.options.legend.display = showLegend;
        };

        /**
         * Switches the scale between linear and logarithmic
         * @param chart
         */
        let switchScale = (chart) => {
            let type = chart.options.scales.yAxes[0].type === 'linear' ? 'logarithmic' : 'linear';

            chart.options.scales.yAxes[0] = {
                type: type
            };
            chart.update();
        };

        /**
         *
         * @param number
         * @returns {string}
         */
        let numberWithSign = (number) => {
            return (number > 0 ? '+' : '') + number.toString();
        };

        // Gets data
        let dataReq = new XMLHttpRequest();
        dataReq.open('GET', '{{ $region ? route('api:region', [$region]) : route('api:total') }}');
        dataReq.responseType = 'json';
        dataReq.send();

        dataReq.onload = () => {
            let data = dataReq.response;
            let labels = Object.keys(data).map(label => label.split(' ')[0]);
            let values = Object.values(data);
            let hospitalized_home = values.map(datum => datum.hospitalized_home);
            let hospitalized_light = values.map(datum => datum.hospitalized_light);
            let hospitalized_severe = values.map(datum => datum.hospitalized_severe);
            let ill = values.map(datum => (datum.hospitalized_home + datum.hospitalized_light + datum.hospitalized_severe));
            let healed = values.map(datum => datum.healed);
            let dead = values.map(datum => datum.dead);
            let tested = values.map(datum => datum.tested);
            let new_cases = ill.map((item, key) => {
                return key === 0 ? item : item - ill[key - 1];
            });

            // Number boxes
            let total_ill = ill.slice(-1)[0];
            let diff_ill = total_ill - ill.slice(-2)[0];
            document.getElementById('total-ill').textContent = total_ill.toString();
            document.getElementById('diff-ill').textContent = numberWithSign(diff_ill);

            let total_healed = healed.slice(-1)[0];
            let diff_healed = total_healed - healed.slice(-2)[0];
            document.getElementById('total-healed').textContent = total_healed.toString();
            document.getElementById('diff-healed').textContent = numberWithSign(diff_healed);

            let total_dead = dead.slice(-1)[0];
            let diff_dead = total_dead - dead.slice(-2)[0];
            document.getElementById('total-dead').textContent = total_dead.toString();
            document.getElementById('diff-dead').textContent = numberWithSign(diff_dead);

            let total_infected = total_ill + total_healed + total_dead;
            let diff_infected = diff_ill + diff_healed + diff_dead;
            document.getElementById('total-infected').textContent = total_infected.toString();
            document.getElementById('diff-infected').textContent = numberWithSign(diff_infected);

            let total_tested = tested.slice(-1)[0];
            document.getElementById('total-tested').textContent = total_tested.toString();
            document.getElementById('diff-tested').textContent = numberWithSign(total_tested - tested.slice(-2)[0]);

            document.getElementById('lethality').textContent = (Math.round(10000 * total_dead / total_infected) / 100).toString() + '%';

            let total_hospitalized_light = hospitalized_light.slice(-1)[0];
            let diff_hospitalized_light = total_hospitalized_light - hospitalized_light.slice(-2)[0];
            document.getElementById('total-hospitalized-light').textContent = total_hospitalized_light.toString();

            document.getElementById('diff-hospitalized-light').textContent = numberWithSign(diff_hospitalized_light);
            let total_hospitalized_severe = hospitalized_severe.slice(-1)[0];
            let diff_hospitalized_severe = total_hospitalized_severe - hospitalized_severe.slice(-2)[0];
            document.getElementById('total-hospitalized-severe').textContent = total_hospitalized_severe.toString();

            document.getElementById('diff-hospitalized-severe').textContent = numberWithSign(diff_hospitalized_severe);
            document.getElementById('total-hospitalized').textContent = total_hospitalized_light + total_hospitalized_severe;

            document.getElementById('diff-hospitalized').textContent = numberWithSign(diff_hospitalized_light + diff_hospitalized_severe);

            for (let k in data) {
                if (!data.hasOwnProperty(k)) continue;
                let datum = data[k];

                let tr = document.createElement('tr');

                let td_date = document.createElement('td');
                td_date.innerText = k.split(' ')[0];
                let td_hospitalized_home = document.createElement('td');
                td_hospitalized_home.innerText = datum.hospitalized_home;
                let td_hospitalized_light = document.createElement('td');
                td_hospitalized_light.innerText = datum.hospitalized_light;
                let td_hospitalized_severe = document.createElement('td');
                td_hospitalized_severe.innerText = datum.hospitalized_severe;
                let td_healed = document.createElement('td');
                td_healed.innerText = datum.healed;
                let td_dead = document.createElement('td');
                td_dead.innerText = datum.dead;
                let td_tested = document.createElement('td');
                td_tested.innerText = datum.tested;

                tr.appendChild(td_date);
                tr.appendChild(td_hospitalized_home);
                tr.appendChild(td_hospitalized_light);
                tr.appendChild(td_hospitalized_severe);
                tr.appendChild(td_healed);
                tr.appendChild(td_dead);
                tr.appendChild(td_tested);

                document.getElementById('data-table').appendChild(tr);
            }

            // Charts
            let ill_healed_dead_chart = new Chart(ill_healed_dead, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __('dash.ill') }}',
                            data: ill,
                            backgroundColor: 'rgba(255, 255, 255, 0)',
                            borderColor: 'red',
                            borderWidth: 1,
                            lineTension: 0,
                            type: 'line'
                        },
                        {
                            label: '{{ __('dash.healed') }}',
                            data: healed,
                            backgroundColor: 'rgba(255, 255, 255, 0)',
                            borderColor: 'green',
                            borderWidth: 1,
                            lineTension: 0,
                            type: 'line'
                        },
                        {
                            label: '{{ __('dash.dead') }}',
                            data: dead,
                            backgroundColor: 'rgba(255, 255, 255, 0)',
                            borderColor: 'black',
                            borderWidth: 1,
                            lineTension: 0,
                            type: 'line'
                        },
                        {
                            label: '{{ __('dash.new_cases') }}',
                            data: new_cases,
                            backgroundColor: 'rgb(255,182,194)',
                            borderColor: 'rgb(255,182,194)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    onResize: chartOnResize,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                    },
                    legend: {
                        display: (ill_healed_dead.parentElement.clientWidth > 800)
                    }
                }
            });

            let ill_by_severity_chart = new Chart(ill_by_severity, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __('dash.hospitalized_home') }}',
                            data: hospitalized_home,
                            backgroundColor: 'green',
                            borderColor: 'green',
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_light') }}',
                            data: hospitalized_light,
                            backgroundColor: 'orange',
                            borderColor: 'orange',
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_severe') }}',
                            data: hospitalized_severe,
                            backgroundColor: 'red',
                            borderColor: 'red',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    onResize: chartOnResize,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            stacked: true
                        }],
                        xAxes: [{
                            stacked: true
                        }]
                    },
                    legend: {
                        display: (ill_by_severity.parentElement.clientWidth > 800)
                    }
                }
            });

            let ill_by_severity_lines_chart = new Chart(ill_by_severity_lines, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __('dash.hospitalized_home') }}',
                            data: hospitalized_home,
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderColor: 'green',
                            lineTension: 0,
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_light') }}',
                            data: hospitalized_light,
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderColor: 'orange',
                            lineTension: 0,
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_severe') }}',
                            data: hospitalized_severe,
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderColor: 'red',
                            lineTension: 0,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    onResize: chartOnResize,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                        }],
                        xAxes: [{}]
                    },
                    legend: {
                        display: (ill_by_severity_lines.parentElement.clientWidth > 800)
                    }
                }
            });

            // Switch buttons
            ill_healed_dead.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
                switchScale(ill_healed_dead_chart);
            });
            ill_by_severity_lines.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
                switchScale(ill_by_severity_lines_chart);
            });
        };
    </script>

    @if(!$region)
        <script>
            function getColor(min, max, value) {
                let interval = Math.round((max - min) * 100 / 8) / 100;
                return value > interval * 8 ? '#800026' :
                    value > interval * 7 ? '#BD0026' :
                        value > interval * 6 ? '#E31A1C' :
                            value > interval * 5 ? '#FC4E2A' :
                                value > interval * 4 ? '#FD8D3C' :
                                    value > interval * 3 ? '#FEB24C' :
                                        value > interval * 2 ? '#FED976' :
                                            '#FFEDA0';
            }

            // Maps
            let map_ill = L.map('map_ill', {zoomSnap: 0.2}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
            let map_infected = L.map('map_infected', {zoomSnap: 0.2}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
            let map_dead = L.map('map_dead', {zoomSnap: 0.2}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map_ill);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map_infected);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map_dead);

            let regionsReq = new XMLHttpRequest();
            regionsReq.open('GET', '{{ asset('data/regions.geojson') }}');
            regionsReq.responseType = 'json';
            regionsReq.onload = () => {
                let mapDataReq = new XMLHttpRequest();

                mapDataReq.open('GET', '{{ route('api:regions.incidence') }}');
                mapDataReq.responseType = 'json';
                mapDataReq.onload = () => {
                    let mapData = mapDataReq.response;
                    let minIll = Math.min(...Object.keys(mapData).map(key => mapData[key].ill));
                    let maxIll = Math.max(...Object.keys(mapData).map(key => mapData[key].ill));
                    let minInfected = Math.min(...Object.keys(mapData).map(key => mapData[key].infected));
                    let maxInfected = Math.max(...Object.keys(mapData).map(key => mapData[key].infected));
                    let minDead = Math.min(...Object.keys(mapData).map(key => mapData[key].dead));
                    let maxDead = Math.max(...Object.keys(mapData).map(key => mapData[key].dead));

                    L.geoJson(regionsReq.response, {
                        style: (feature) => {
                            return {
                                fillColor: getColor(minIll, maxIll, mapData[feature.properties.DatabaseID].ill),
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            }
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].ill}`)
                        }
                    }).addTo(map_ill);
                    L.geoJson(regionsReq.response, {
                        style: (feature) => {
                            return {
                                fillColor: getColor(minInfected, maxInfected, mapData[feature.properties.DatabaseID].infected),
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            }
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].infected}`)
                        }
                    }).addTo(map_infected);
                    L.geoJson(regionsReq.response, {
                        style: (feature) => {
                            return {
                                fillColor: getColor(minDead, maxDead, mapData[feature.properties.DatabaseID].dead),
                                weight: 2,
                                opacity: 1,
                                color: 'white',
                                dashArray: '3',
                                fillOpacity: 0.7
                            }
                        },
                        onEachFeature: (feature, layer) => {
                            layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].dead}`)
                        }
                    }).addTo(map_dead);
                };
                mapDataReq.send();
            };
            regionsReq.send();

        </script>
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
        .card-chart {
            width: 100%;
            height: 60vh;
        }

        .map-container {
            height: 700px;
        }
    </style>
@stop
