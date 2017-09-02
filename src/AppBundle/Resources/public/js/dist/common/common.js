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
        accessToken : '',
        info : null,
        informationOnOverEnabled: false,
        clickAction: function(){}
    };

    this.construct = function(options){
        $.extend(vars , options);
        vars.mymap = L.map(vars.mapName).setView([23.872326, 10.586547], 2); // .fitWorld();

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: vars.accessToken
        }).addTo(vars.mymap);

        vars.info = L.control();
    };

    this.printCountries = function(onEachFeature){
        onEachFeature = onEachFeature || null;
        //$.getJSON("../assets/geo-countries/data/countries.geojson", function (statesData) {
        $.getJSON("../files/countries.geojson", function (statesData) {
            $.each(statesData.features, function (i) {
                var feature = statesData.features[i];

                feature.properties.price = feature.properties.totalPrices.toFixed(0);

            });
            statesData.features = jQuery.grep(statesData.features, function(feature) {
                return feature.properties.price;
            });

            L.geoJson(statesData, {style: style, onEachFeature: onEachFeature}).addTo(vars.mymap);
        });
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

    var highlightFeature = function(e) {
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

    var resetHighlight = function(e){
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
            var colorSelected = "#0C9915";
            var target = e.target;
            if (target.options.fillColor != colorSelected) {
                target.setStyle({fillColor: colorSelected});
            } else {
                target.setStyle(style(target.feature))
            }
        };
    };

    this.setClickActionFollowURL = function() {
        vars.clickAction = function(e) {
            window.location.href = e.target.feature.properties.viewUrl;
        };
    };

    this.onEachFeatureCountry = function(feature, layer) {
        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: vars.clickAction
        });
    };

    this.enableLegend = function() {
        var legend = L.control({position: 'bottomleft'});

        legend.onAdd = function (map) {

            var div = L.DomUtil.create('div', 'info legend'),
                grades = [10, 20, 30, 40, 50];

            for (var i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + getColor(grades[i] + 1) + '"></i>' +
                    grades[i] + (grades[i + 1] ? ' à ' + grades[i + 1] + ' €<br>' : '+ €') + '';
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
            (props ? '<b>' + props.name + ' - ' + props.price + '€/jour</b>' +
            '<img src="http://www.geonames.org/flags/x/' + props.codeAlpha2.toLowerCase() + '.gif" class="flagCountrySmall flagGmap pull-right" alt="drapeau ' + props.name + '">'
                : 'Survoler un pays');
        };

        vars.info.addTo(vars.mymap);
    };

    this.printMarker = function(markerLocation) {
        var marker = L.marker(markerLocation).addTo(vars.mymap);
    };


    this.construct(options);
};

