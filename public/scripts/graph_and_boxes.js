// Canvases
let healed_dead = document.getElementById('healed_dead');
let ill_box = document.getElementById('ill');
let ill_by_severity = document.getElementById('ill_by_severity');
let ill_variations = document.getElementById('ill_variations');
let ill_weighted_variations = document.getElementById('ill_weighted_variations');
let ill_by_severity_lines = document.getElementById('ill_by_severity_lines');
let daily_vaccinations_lines = document.getElementById('daily_vaccinations_lines');
let vaccinations_and_shipments_lines = document.getElementById('vaccinations_and_shipments_lines');
let immuni_downloads_lines = document.getElementById('immuni_downloads_lines') || null;

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

// Gets DPC data
let dataReq = new XMLHttpRequest();
dataReq.open('GET', document.getElementById('js-graph-and-boxes').dataset.dataUrl);
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
    let infected = values.map(datum => (datum.hospitalized_home + datum.hospitalized_light + datum.hospitalized_severe + datum.healed + datum.dead));
    let healed = values.map(datum => datum.healed);
    let dead = values.map(datum => datum.dead);
    let tests = values.map(datum => datum.tests);
    let tested = values.map(datum => datum.tested);
    let daily_doses = values.map(datum => datum.daily_doses);
    let doses = daily_doses.map((datum, i) => daily_doses.slice(0, i + 1).reduce((a, b) => a + b));
    let daily_final_doses = values.map(datum => datum.daily_final_doses);
    let final_doses = daily_final_doses.map((datum, i) => daily_final_doses.slice(0, i + 1).reduce((a, b) => a + b));
    let daily_vaccine_shipments = values.map(datum => datum.daily_vaccine_shipments);
    let vaccine_shipments = daily_vaccine_shipments.map((datum, i) => daily_vaccine_shipments.slice(0, i + 1).reduce((a, b) => a + b));
    let new_ill = ill.map((item, key) => {
        return key === 0 ? item : item - ill[key - 1];
    });
    let new_infected = infected.map((item, key) => {
        return key === 0 ? item : item - infected[key - 1];
    });
    let new_tested = tested.map((item, key) => {
        return key === 0 ? item : item - tested[key - 1];
    });
    let new_weighted_infected = new_infected.map((item, key) => {
        return item / new_tested[key];
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

    let total_tests = tests.slice(-1)[0];
    document.getElementById('total-tests').textContent = total_tests.toString();
    document.getElementById('diff-tests').textContent = numberWithSign(total_tests - tests.slice(-2)[0]);

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

    let total_doses = daily_doses.reduce((a, c) => a + c, 0);
    document.getElementById('total-vaccine_doses').textContent = total_doses.toString();
    document.getElementById('diff-vaccine_doses').textContent = numberWithSign(daily_doses.slice(-1)[0]);

    let total_final_doses = daily_final_doses.reduce((a, c) => a + c, 0);
    document.getElementById('total-final_vaccine_doses').textContent = total_final_doses.toString();
    document.getElementById('diff-final_vaccine_doses').textContent = numberWithSign(daily_final_doses.slice(-1)[0]);

    let total_vaccine_shipments = daily_vaccine_shipments.reduce((a, c) => a + c, 0);
    document.getElementById('total-vaccine-shipments').textContent = total_vaccine_shipments.toString();
    document.getElementById('diff-vaccine-shipments').textContent = numberWithSign(daily_vaccine_shipments.slice(-1)[0]);

    // Charts
    let ill_chart = new Chart(ill_box, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: ill_box.dataset.labelIll,
                    data: ill,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    borderColor: 'red',
                    borderWidth: 1,
                    lineTension: 0,
                    type: 'line'
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
                display: (ill_box.parentElement.clientWidth > 800)
            }
        }
    });

    let healed_dead_chart = new Chart(healed_dead, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: healed_dead.dataset.labelHealed,
                    data: healed,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    borderColor: 'green',
                    borderWidth: 1,
                    lineTension: 0,
                    type: 'line'
                },
                {
                    label: healed_dead.dataset.labelDead,
                    data: dead,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    borderColor: 'black',
                    borderWidth: 1,
                    lineTension: 0,
                    type: 'line'
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
                display: (healed_dead.parentElement.clientWidth > 800)
            }
        }
    });

    let ill_variations_chart = new Chart(ill_variations, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: ill_variations.dataset.labelNewIll,
                    data: new_ill,
                    backgroundColor: 'rgb(255,182,194)',
                    borderColor: 'rgb(255,182,194)',
                    borderWidth: 1
                },
                {
                    label: ill_variations.dataset.labelNewInfected,
                    data: new_infected,
                    backgroundColor: 'rgba(111,66,193,0.42)',
                    borderColor: 'rgba(111,66,193,0.42)',
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
                display: (ill_variations.parentElement.clientWidth > 800)
            }
        }
    });

    let ill_weighted_variations_chart = new Chart(ill_weighted_variations, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: ill_weighted_variations.dataset.labelNewInfected,
                    data: new_weighted_infected,
                    backgroundColor: 'rgba(111,66,193,0.42)',
                    borderColor: 'rgba(111,66,193,0.42)',
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
                display: (ill_variations.parentElement.clientWidth > 800)
            }
        }
    });

    let ill_by_severity_chart = new Chart(ill_by_severity, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: ill_by_severity.dataset.labelHome,
                    data: hospitalized_home,
                    backgroundColor: 'green',
                    borderColor: 'green',
                    borderWidth: 1
                },
                {
                    label: ill_by_severity.dataset.labelLight,
                    data: hospitalized_light,
                    backgroundColor: 'orange',
                    borderColor: 'orange',
                    borderWidth: 1
                },
                {
                    label: ill_by_severity.dataset.labelSevere,
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
                    label: ill_by_severity_lines.dataset.labelHome,
                    data: hospitalized_home,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'green',
                    lineTension: 0,
                    borderWidth: 1
                },
                {
                    label: ill_by_severity_lines.dataset.labelLight,
                    data: hospitalized_light,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: 'orange',
                    lineTension: 0,
                    borderWidth: 1
                },
                {
                    label: ill_by_severity_lines.dataset.labelSevere,
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

    let daily_vaccinations_lines_chart = new Chart(daily_vaccinations_lines, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: daily_vaccinations_lines.dataset.labelVaccinations,
                    data: daily_doses,
                    backgroundColor: '#C71585',
                    borderColor: '#C71585',
                    borderWidth: 1
                },
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
                display: (daily_vaccinations_lines.parentElement.clientWidth > 800)
            }
        }
    });

    let vaccinations_and_shipments_chart = new Chart(vaccinations_and_shipments_lines, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: vaccinations_and_shipments_lines.dataset.labelVaccinations,
                    data: doses,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#C71585',
                    borderWidth: 1,
                },
                {
                    label: vaccinations_and_shipments_lines.dataset.labelFinalVaccinations,
                    data: final_doses,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#c7152a',
                    borderWidth: 1
                },
                {
                    label: vaccinations_and_shipments_lines.dataset.labelShipments,
                    data: vaccine_shipments,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderColor: '#152dc7',
                    borderWidth: 1
                },
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
                display: (daily_vaccinations_lines.parentElement.clientWidth > 800)
            }
        }
    });

    // Switch buttons
    ill_box.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
        switchScale(ill_chart);
    });
    healed_dead.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
        switchScale(healed_dead_chart);
    });
    ill_by_severity_lines.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
        switchScale(ill_by_severity_lines_chart);
    });
};

// Gets Immuni downloads data
if (document.getElementById('js-graph-and-boxes').dataset.immuniDlUrl) {
    let immuniDlReq = new XMLHttpRequest();
    immuniDlReq.open('GET', document.getElementById('js-graph-and-boxes').dataset.immuniDlUrl);
    immuniDlReq.responseType = 'json';
    immuniDlReq.send();

    immuniDlReq.onload = () => {
        let immuniDlData = immuniDlReq.response;

        let immuni_downloads_chart = new Chart(immuni_downloads_lines, {
            type: 'bar',
            data: {
                labels: [...Object.keys(immuniDlData)],
                datasets: [
                    {
                        label: immuni_downloads_lines.dataset.labelAndroid,
                        data: [...Object.keys(immuniDlData)].map((k => immuniDlData[k].android_downloads)),
                        backgroundColor: '#3DDC84',
                        borderColor: '#3DDC84',
                    },
                    {
                        label: immuni_downloads_lines.dataset.labelIos,
                        data: [...Object.keys(immuniDlData)].map((k => immuniDlData[k].ios_downloads)),
                        backgroundColor: '#147efb',
                        borderColor: '#147efb',
                    },
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
                    display: (ill_box.parentElement.clientWidth > 800)
                }
            }
        });
    }
}
