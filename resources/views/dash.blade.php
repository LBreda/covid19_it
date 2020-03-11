@extends('adminlte::page')

@section('title', 'COVID-19 IT | ' . ($region ? $region->name : 'Dati Nazionali'))

@section('content_header')
    <h1>{{ $region ? $region->name : 'Dati Nazionali' }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Malati</span>
                    <span class="info-box-number">{{ $total_ill }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-smile"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Guariti</span>
                    <span class="info-box-number">{{ $total_healed }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-black"><i class="fa fa-plus-square"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Deceduti</span>
                    <span class="info-box-number">{{ $total_dead }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-ambulance"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Infettati</span>
                    <span class="info-box-number">{{ $total_infected }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-user-md"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tamponi</span>
                    <span class="info-box-number">{{ $total_tested }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-bolt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Letalità</span>
                    <span class="info-box-number">{{ round($letality, 2) }}%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12"><p>Nota: è presto perché la letalità sia un dato sensato: prendetelo con le pinze (e
                lavatevi le mani poi).</p></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Malati, guariti, deceduti</h3>
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
                    <h3 class="card-title">Malati per tipo di ricovero</h3>
                </div>

                <div class="card-body">
                    <canvas id="ill_by_severity" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    <footer class="row mt-4">
        <div class="col-md-12 text-center" style="font-size: 80%">
            <p>Fonte dati: <a href="https://github.com/pcm-dpc/COVID-19">Protezione Civile Nazionale</a>.</p>
            <p>Creato da <a href="https://lbreda.com/">Lorenzo Breda</a> e sostenuto da <a
                    href="https://twobeesolution.com">TwoBeeSolution S.r.l.</a></p>
            <p>Questo sito è Open Source e <a href="https://github.com/LBreda/covid19_it">disponibile su GitHub</a> -
                Puoi
                <a href="https://github.com/LBreda/covid19_it/issues">segnalare bug qui</a>.</p>
        </div>
    </footer>
@stop

@section('js')
    <script>
        let graphOnResize = (chart, size) => {
            let showLegend = size.width > 800;
            chart.options.legend.display = showLegend;
        };

        let ill_healed_dead = document.getElementById('ill_healed_dead');
        new Chart(ill_healed_dead, {
            type: 'bar',
            data: {
                labels: {!! $labels->toJson() !!},
                datasets: [
                    {
                        label: 'Attualmente malati',
                        data: {!! $ill->toJson() !!},
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        borderColor: 'red',
                        borderWidth: 1,
                        lineTension: 0,
                        type: 'line'
                    },
                    {
                        label: 'Guariti',
                        data: {!! $healed->toJson() !!},
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        borderColor: 'green',
                        borderWidth: 1,
                        lineTension: 0,
                        type: 'line'
                    },
                    {
                        label: 'Deceduti',
                        data: {!! $dead->toJson() !!},
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        borderColor: 'black',
                        borderWidth: 1,
                        lineTension: 0,
                        type: 'line'
                    },
                    {
                        label: 'Nuovi casi (nuovi positivi meno nuovi dimessi)',
                        data: {!! $new_cases->toJson() !!},
                        backgroundColor: 'rgb(255,182,194)',
                        borderColor: 'rgb(255,182,194)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                onResize: graphOnResize,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: (ill_healed_dead.parentElement.clientWidth > 800)
                }
            }
        });

        let ill_by_severity = document.getElementById('ill_by_severity');
        new Chart(ill_by_severity, {
            type: 'bar',
            data: {
                labels: {!! $labels->toJson() !!},
                datasets: [
                    {
                        label: 'Isolamento domestico',
                        data: {!! $hospitalized_home->toJson() !!},
                        backgroundColor: 'green',
                        borderColor: 'green',
                        borderWidth: 1
                    },
                    {
                        label: 'Ospedale - Non T. intensiva',
                        data: {!! $hospitalized_light->toJson() !!},
                        backgroundColor: 'orange',
                        borderColor: 'orange',
                        borderWidth: 1
                    },
                    {
                        label: 'Terapia intensiva',
                        data: {!! $hospitalized_severe->toJson() !!},
                        backgroundColor: 'red',
                        borderColor: 'red',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                onResize: graphOnResize,
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
    </script>
@stop

