function mapdataToArray(mapData, key) {
    return [...Object.keys(mapData).map(k => mapData[k][key])];
}

function getColor(dataset, value, colors) {
    let min = Math.min(...dataset);
    let max = Math.max(...dataset);
    let step = Math.round((max - min) * 100 / (colors.length - 1));
    let interval_no = Math.floor((value - min) * 100 / step);
    console.log(interval_no);
    return colors[interval_no];
}

let colors_ill = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C', '#BD0026', '#800026',];
let colors_infected = ['#fcfbfd', '#efedf5', '#dadaeb', '#bcbddc', '#9e9ac8', '#807dba', '#6a51a3', '#4a1486',];
let colors_dead = ['#ffffff', '#f0f0f0', '#d9d9d9', '#bdbdbd', '#969696', '#737373', '#525252', '#252525',];
let colors_tested = ['#ffffff', '#edf8e9', '#c7e9c0', '#a1d99b', '#74c476', '#41ab5d', '#238b45', '#005a32',];
let colors_doses = ['#ffffff', '#F7DEEE', '#EFBCDC', '#E79BCB', '#DF79B9', '#D758A8', '#CF3696', '#C71585',]

// Maps
let map_options = {
    zoomSnap: 0.2,
    dragging: !L.Browser.mobile,
    tap: !L.Browser.mobile
};
let map_view = [41.893056, 12.482778];
let map_zoom = 5;
let map_bounds = [[47.0727778, 6.6255556], [35.49, 18.5216667]];
let map_tiles = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
let map_tiles_options = {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
};
let map_style = {
    fillColor: '',
    weight: 2,
    opacity: 1,
    color: 'white',
    dashArray: '3',
    fillOpacity: 0.7
}

let map_ill = L.map('map_ill', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_infected = L.map('map_infected', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_dead = L.map('map_dead', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_tested = L.map('map_tested', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_restrictions = L.map('map_restrictions', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_doses = L.map('map_doses', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_final_doses = L.map('map_final_doses', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);
let map_vaccine_shipments = L.map('map_vaccine_shipments', map_options).setView(map_view, map_zoom).fitBounds(map_bounds);

L.tileLayer(map_tiles, map_tiles_options).addTo(map_ill);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_infected);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_dead);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_tested);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_restrictions);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_doses);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_final_doses);
L.tileLayer(map_tiles, map_tiles_options).addTo(map_vaccine_shipments);

let regionsReq = new XMLHttpRequest();
regionsReq.open('GET', document.getElementById('js-maps').dataset.geoUrl);
regionsReq.responseType = 'json';
regionsReq.onload = () => {
    let mapDataReq = new XMLHttpRequest();

    mapDataReq.open('GET', document.getElementById('js-maps').dataset.incidenceUrl);
    mapDataReq.responseType = 'json';
    mapDataReq.onload = () => {
        let mapData = mapDataReq.response;

        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'ill'), mapData[feature.properties.DatabaseID].ill, colors_ill);
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].ill}`)
            }
        }).addTo(map_ill);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'infected'), mapData[feature.properties.DatabaseID].infected, colors_infected)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].infected}`)
            }
        }).addTo(map_infected);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'dead'), mapData[feature.properties.DatabaseID].dead, colors_dead)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].dead}`)
            }
        }).addTo(map_dead);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'tested'), mapData[feature.properties.DatabaseID].tested, colors_tested)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].tested}`)
            }
        }).addTo(map_tested);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = mapData[feature.properties.DatabaseID].severity.color
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}`)
            }
        }).addTo(map_restrictions);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'daily_doses'), mapData[feature.properties.DatabaseID].daily_doses, colors_doses)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].daily_doses}`)
            }
        }).addTo(map_doses);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'daily_final_doses'), mapData[feature.properties.DatabaseID].daily_final_doses, colors_doses)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].daily_final_doses}`)
            }
        }).addTo(map_final_doses);
        L.geoJson(regionsReq.response, {
            style: (feature) => {
                let style = map_style;
                style.fillColor = getColor(mapdataToArray(mapData, 'daily_vaccine_shipments'), mapData[feature.properties.DatabaseID].daily_vaccine_shipments, colors_doses)
                return style;
            },
            onEachFeature: (feature, layer) => {
                layer.bindTooltip(`${feature.properties.Regione}: ${mapData[feature.properties.DatabaseID].daily_vaccine_shipments}`)
            }
        }).addTo(map_vaccine_shipments);
    };
    mapDataReq.send();
};
regionsReq.send();
