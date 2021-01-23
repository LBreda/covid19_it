function getColorIll(min, max, value) {
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

function getColorInfected(min, max, value) {
    let interval = Math.round((max - min) * 100 / 8) / 100;
    return value > interval * 8 ? '#4a1486' :
        value > interval * 7 ? '#6a51a3' :
            value > interval * 6 ? '#807dba' :
                value > interval * 5 ? '#9e9ac8' :
                    value > interval * 4 ? '#bcbddc' :
                        value > interval * 3 ? '#dadaeb' :
                            value > interval * 2 ? '#efedf5' :
                                '#fcfbfd';
}

function getColorDead(min, max, value) {
    let interval = Math.round((max - min) * 100 / 8) / 100;
    return value > interval * 8 ? '#252525' :
        value > interval * 7 ? '#525252' :
            value > interval * 6 ? '#737373' :
                value > interval * 5 ? '#969696' :
                    value > interval * 4 ? '#bdbdbd' :
                        value > interval * 3 ? '#d9d9d9' :
                            value > interval * 2 ? '#f0f0f0' :
                                '#ffffff';
}

function getColorTested(min, max, value) {
    let interval = Math.round((max - min) * 100 / 8) / 100;
    return value > interval * 8 ? '#005a32' :
        value > interval * 7 ? '#238b45' :
            value > interval * 6 ? '#41ab5d' :
                value > interval * 5 ? '#74c476' :
                    value > interval * 4 ? '#a1d99b' :
                        value > interval * 3 ? '#c7e9c0' :
                            value > interval * 2 ? '#edf8e9' :
                                '#ffffff';
}

function getColorDoses(min, max, value) {
    let interval = Math.round((max - min) * 100 / 8) / 100;
    return value > interval * 8 ? '#C71585' :
        value > interval * 7 ? '#CF3696' :
            value > interval * 6 ? '#D758A8' :
                value > interval * 5 ? '#DF79B9' :
                    value > interval * 4 ? '#E79BCB' :
                        value > interval * 3 ? '#EFBCDC' :
                            value > interval * 2 ? '#F7DEEE' :
                                '#ffffff';
}

// Maps
let map_ill = L.map('map_ill', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
let map_infected = L.map('map_infected', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
let map_dead = L.map('map_dead', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
let map_tested = L.map('map_tested', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
let map_restrictions = L.map('map_restrictions', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
let map_doses = L.map('map_doses', {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
}).setView([41.893056, 12.482778], 5).fitBounds([[47.0727778, 6.6255556], [35.49, 18.5216667]]);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_ill);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_infected);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_dead);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_tested);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_restrictions);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map_doses);

let regionsReq = new XMLHttpRequest();
regionsReq.open('GET', document.getElementById('js-maps').dataset.geoUrl);
regionsReq.responseType = 'json';
regionsReq.onload = () => {
    let mapDataReq = new XMLHttpRequest();

    mapDataReq.open('GET', document.getElementById('js-maps').dataset.incidenceUrl);
    mapDataReq.responseType = 'json';
    mapDataReq.onload = () => {
        let mapData = mapDataReq.response;
        let minIll = Math.min(...Object.keys(mapData).map(key => mapData[key].ill));
        let maxIll = Math.max(...Object.keys(mapData).map(key => mapData[key].ill));
        let minInfected = Math.min(...Object.keys(mapData).map(key => mapData[key].infected));
        let maxInfected = Math.max(...Object.keys(mapData).map(key => mapData[key].infected));
        let minDead = Math.min(...Object.keys(mapData).map(key => mapData[key].dead));
        let maxDead = Math.max(...Object.keys(mapData).map(key => mapData[key].dead));
        let minTested = Math.min(...Object.keys(mapData).map(key => mapData[key].tested));
        let maxTested = Math.max(...Object.keys(mapData).map(key => mapData[key].tested));
        let minDoses = Math.min(...Object.keys(mapData).map(key => mapData[key].daily_doses));
        let maxDoses = Math.max(...Object.keys(mapData).map(key => mapData[key].daily_doses));

        L.geoJson(regionsReq.response, {
            style: (feature) => {
                return {
                    fillColor: getColorIll(minIll, maxIll, mapData[feature.properties.DatabaseID].ill),
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
                    fillColor: getColorInfected(minInfected, maxInfected, mapData[feature.properties.DatabaseID].infected),
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
                    fillColor: getColorDead(minDead, maxDead, mapData[feature.properties.DatabaseID].dead),
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
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                return {
                    fillColor: getColorTested(minTested, maxTested, mapData[feature.properties.DatabaseID].tested),
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7
                }
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].tested}`)
            }
        }).addTo(map_tested);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                return {
                    fillColor: mapData[feature.properties.DatabaseID].severity.color,
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.9
                }
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}`)
            }
        }).addTo(map_restrictions);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                return {
                    fillColor: getColorDoses(minDoses, maxDoses, mapData[feature.properties.DatabaseID].daily_doses),
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.9
                }
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].daily_doses}`)
            }
        }).addTo(map_doses);
    };
    mapDataReq.send();
};
regionsReq.send();
