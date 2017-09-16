$().ready(function () {

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $.material.init();


    $('.columnsFooter').masonry({
        itemSelector: '.country',
        columnWidth: ".country",
        percentPosition: true
    });

});


// http://leafletjs.com/examples/choropleth/
var map = function(options){

    var vars = {
        mymap  : null,
        mapName : 'map',
        info : null,
        informationOnOverEnabled: false,
        clickAction: function(){}
    };
    var self = this;
    var colorSelected = "#0C9915";

    this.construct = function(options){
        $.extend(vars , options);
        vars.mymap = L.map(vars.mapName);
        setDefaultZoom();

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: accessTokenMapbox
        }).addTo(vars.mymap);

        vars.info = L.control();
    };

    this.printCountries = function(onEachFeature, countriesToSelect){
        onEachFeature = onEachFeature || null;
        countriesToSelect = countriesToSelect || [];
        $.getJSON(geoJsonMapPath, function (statesData) {
            $.each(statesData.features, function (i) {
                var feature = statesData.features[i];

                feature.properties.price = feature.properties.totalPrices.toFixed(0);
                feature.selected = false;
            });
            statesData.features = jQuery.grep(statesData.features, function(feature) {
                return feature.properties.price;
            });

            L.geoJson(statesData, {style: style, onEachFeature: onEachFeature}).addTo(vars.mymap);
            setDefaultZoom();

            self.selectCountries(countriesToSelect);
        });
    };

    this.selectCountries = function(countries) {
        $.each(vars.mymap._layers, function(ml){
            var layer = vars.mymap._layers[ml];
            if(layer.feature && layer.feature.properties.ISO_A3) {
                var iso3CurrentCountry = layer.feature.properties.ISO_A3;
                layer.feature.selected = countries.indexOf(iso3CurrentCountry) > -1;
                selectCountry(layer);
            }
        });
    };

    var selectCountry = function(target) {
        if (target.feature.selected) {
            target.setStyle({fillColor: colorSelected});
        } else {
            target.setStyle(style(target.feature));
            target.feature.selected = false;
        }
    };

    var setDefaultZoom = function() {
        vars.mymap.setView([23.872326, 10.586547], 2); //.fitWorld();
    };

    var getColor = function(price) {
        return price == null ? '#B3B191' :
            price > 50  ? '#B37631' :
            price > 40  ? '#C98C47' :
            price > 30   ? '#D4A36C' :
            price > 20   ? '#DEBA90' :
            '#E9D1B5';
    };

    var style = function(feature) {
        return {
            fillColor: getColor(feature.properties.price),
            weight: 1,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        };
    };

    var highlightFeatureCountry = function(e) {
        var layer = e.target;

        layer.setStyle({
            weight: 3,
            color: '#666',
            dashArray: '',
            fillOpacity: 0.7
        });

        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
            layer.bringToFront();
        }

        if (vars.informationOnOverEnabled) {
            vars.info.update(layer.feature.properties);
        }
    };

    var resetHighlightCountry = function(e){
        var layer = e.target;

        layer.setStyle({
            weight: 1,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        });

        if (vars.informationOnOverEnabled) {
            vars.info.update();
        }
    };

    this.setClickActionSelectCountry = function() {
        vars.clickAction = function(e) {
            var target = e.target;
            target.feature.selected = target.options.fillColor != colorSelected;
            selectCountry(target);
        };
    };

    this.setClickActionFollowURL = function() {
        vars.clickAction = function(e) {
            window.location.href = e.target.feature.properties.viewUrl;
        };
    };

    this.onEachFeatureCountry = function(feature, layer) {
        layer.on({
            mouseover: highlightFeatureCountry,
            mouseout: resetHighlightCountry,
            click: vars.clickAction
        });
    };

    //var highlightFeatureDestination = function(e) {
    //    var layer = e.target;
    //    console.log(layer);
    //    if (vars.informationOnOverEnabled) {
    //        vars.info.update(layer);
    //    }
    //};
    //
    //var resetHighlightDestination = function() {
    //    if (vars.informationOnOverEnabled) {
    //        vars.info.update();
    //    }
    //};
    //
    //this.onEachFeatureDestination = function(feature, layer) {
    //    layer.on({
    //        mouseover: highlightFeatureDestination,
    //        mouseout: resetHighlightDestination
    //    });
    //};

    this.enableLegend = function(isWithSelectedCountry) {
        var legend = L.control({position: 'bottomleft'});

        legend.onAdd = function (map) {

            var div = L.DomUtil.create('div', 'info legend'),
                grades = [10, 20, 30, 40, 50];

            for (var i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + getColor(grades[i] + 1) + '"></i>' +
                    grades[i] + (grades[i + 1] ? ' à ' + grades[i + 1] + ' €<br>' : '+ €') + '';
            }

            if (isWithSelectedCountry) {
                div.innerHTML +=
                    '<br><i style="background:' + colorSelected + '"></i> Selectionné';
            }

            return div;
        };

        legend.addTo(vars.mymap);
    };

    this.enableInformationOnOver = function() {
        vars.informationOnOverEnabled = true;

        vars.info.onAdd = function (mymap) {
            this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
            this.update();
            return this._div;
        };

        // method that we will use to update the control based on feature properties passed
        vars.info.update = function (props) {
            this._div.innerHTML =
            (props ? '<b>' + props.name + ' - ' + props.price + '€/jour</b> ' +
            ' <img src="http://www.geonames.org/flags/x/' + props.codeAlpha2.toLowerCase() + '.gif" class="flagCountrySmall flagGmap flagGmapInfo pull-right" alt="drapeau ' + props.name + '">'
                : 'Survoler un pays');
        };

        vars.info.addTo(vars.mymap);
    };

    var printMarker = function(destinationData, addPopup, onEachFeature)
    {
        var mapIcon = L.icon({
            iconUrl: destinationData.icon,
            iconSize:     [12, 20], // size of the icon
            iconAnchor:   [6, 20], // point of the icon which will correspond to marker's location
            popupAnchor:  [0, -20] // point from which the popup should open relative to the iconAnchor
        });

        var marker = L.marker([destinationData.lat, destinationData.lon], {icon: mapIcon, onEachFeature: onEachFeature}).addTo(vars.mymap);
        if (addPopup) {
            marker.bindPopup(destinationData.html);
        }
    };


    this.printDestinations = function(addPopup, onEachFeature){
        onEachFeature = onEachFeature || null;
        $.getJSON(getDestinationsInfoUrl, function (destinationsData) {
            var maxLon = null, minLon = null, maxLat = null, minLat = null;

            $.each(destinationsData, function(i){
                var destinationData = destinationsData[i];

                printMarker(destinationData, addPopup, onEachFeature);

                if (maxLon == null) {
                    maxLat = destinationData.lat;
                    minLat = destinationData.lat;
                    maxLon = destinationData.lon;
                    minLon = destinationData.lon
                } else {
                    if (destinationData.lat > maxLat) {
                        maxLat = destinationData.lat;
                    }
                    if(destinationData.lat < minLat) {
                        minLat = destinationData.lat;
                    }
                    if (destinationData.lon > maxLon) {
                        maxLon = destinationData.lon;
                    }
                    if(destinationData.lon < minLon) {
                        minLon = destinationData.lon;
                    }
                }
            });

            vars.mymap.fitBounds([
                [maxLat + 1, minLon - 1],
                [minLat - 1, maxLon + 1]
            ]);

            if (destinationsData.length == 1) {
                vars.mymap.setZoom(9);
            }
        });
    };

    this.getSelectedCountries = function() {
        var selectedCountries = [];
        $.each(vars.mymap._layers, function(ml){
            var layer = vars.mymap._layers[ml];
            if(layer.feature && layer.selected) {
                selectedCountries.push(layer.selected);
            }
        });

        return selectedCountries;
    };


    this.construct(options);
};

