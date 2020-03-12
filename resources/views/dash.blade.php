@extends('adminlte::page')

@section('title', 'COVID-19 IT | ' . ($region ? $region->name : __('dash.national_data')))

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
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-procedures"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.ill') }}</span>
                    <span class="info-box-number" id="total-ill"></span>
                    <small><span class="info-box-number" id="diff-ill"></span></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fas fa-smile"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.healed') }}</span>
                    <span class="info-box-number" id="total-healed"></span>
                    <small><span class="info-box-number" id="diff-healed"></span></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-black"><i class="fas fa-plus-square"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.dead') }}</span>
                    <span class="info-box-number" id="total-dead"></span>
                    <small><span class="info-box-number" id="diff-dead"></span></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fas fa-ambulance"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.infected') }}</span>
                    <span class="info-box-number" id="total-infected"></span>
                    <small><span class="info-box-number" id="diff-infected"></span></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('dash.tested') }}</span>
                    <span class="info-box-number" id="total-tested"></span>
                    <small><span class="info-box-number" id="diff-tested"></span></small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
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
                        <p class="m-0"><strong>{{ $notice->date->format('d/m/Y') }}
                                :</strong> {{ __("dash.notices.{$notice->notice}", ['region' => $notice->region->name]) }}
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

                <div class="card-body">
                    <canvas id="ill_healed_dead" width="400" height='100'></canvas>
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

                <div class="card-body">
                    <canvas id="ill_by_severity" width="400" height="100"></canvas>
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

                <div class="card-body">
                    <canvas id="ill_by_severity_lines" width="400" height="100"></canvas>
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
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_light') }}',
                            data: hospitalized_light,
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderColor: 'orange',
                            borderWidth: 1
                        },
                        {
                            label: '{{ __('dash.hospitalized_severe') }}',
                            data: hospitalized_severe,
                            backgroundColor: 'rgba(0,0,0,0)',
                            borderColor: 'red',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
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

