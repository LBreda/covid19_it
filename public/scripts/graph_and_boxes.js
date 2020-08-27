// Canvases
let ill_healed_dead = document.getElementById('ill_healed_dead');
let ill_by_severity = document.getElementById('ill_by_severity');
let ill_variations = document.getElementById('ill_variations');
let ill_weighted_variations = document.getElementById('ill_weighted_variations');
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
        let td_tests = document.createElement('td');
        td_tests.innerText = datum.tests;

        tr.appendChild(td_date);
        tr.appendChild(td_hospitalized_home);
        tr.appendChild(td_hospitalized_light);
        tr.appendChild(td_hospitalized_severe);
        tr.appendChild(td_healed);
        tr.appendChild(td_dead);
        tr.appendChild(td_tested);
        tr.appendChild(td_tests);

        document.getElementById('data-table').appendChild(tr);
    }

    // Charts
    let ill_healed_dead_chart = new Chart(ill_healed_dead, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: ill_healed_dead.dataset.labelIll,
                    data: ill,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    borderColor: 'red',
                    borderWidth: 1,
                    lineTension: 0,
                    type: 'line'
                },
                {
                    label: ill_healed_dead.dataset.labelHealed,
                    data: healed,
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    borderColor: 'green',
                    borderWidth: 1,
                    lineTension: 0,
                    type: 'line'
                },
                {
                    label: ill_healed_dead.dataset.labelDead,
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
                display: (ill_healed_dead.parentElement.clientWidth > 800)
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

    // Switch buttons
    ill_healed_dead.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
        switchScale(ill_healed_dead_chart);
    });
    ill_by_severity_lines.closest('.card').getElementsByClassName('scale-button')[0].addEventListener('click', () => {
        switchScale(ill_by_severity_lines_chart);
    });
};
